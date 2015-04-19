<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
// Facades
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;
// Requests
use Illuminate\Http\Request;
use App\Http\Requests\User\RegisterRequest;
use App\Http\Requests\User\LoginRequest;
use App\Http\Requests\User\ChangePasswordRequest;
use App\Http\Requests\User\ChangeEmailRequest;
use App\Http\Requests\User\ForgottenPasswordRequest;
use App\Http\Requests\User\PasswordResetRequest;
use App\Http\Requests\User\ChangeAvatarRequest;
// Models
use App\Models\PasswordReset;
use App\Models\User;

class UsersController extends Controller {

    public function __construct() {
        parent::__construct();
        $this->middleware('guest', ['only' => [
                'postLogin',
                'getLogin',
                'postRegister',
                'getRegister',
                'getConfirm',
                'getForgottenPassword',
                'postForgottenPassword',
                'getPasswordReset',
                'postPasswordReset'
            ]
        ]);
        $this->middleware('auth', ['only' => ['getProfile', 'putChangePassword', 'putEmailChange']]);
        $this->middleware('force_https', [
            'only' => [
                'getLogin',
                'postLogin',
                'getRegister',
                'postRegister',
                'getProfile',
                'putChangePassword'
            ]
        ]);
    }

    /**
     * Gets the login page
     * 
     * @return Response
     */
    public function getLogin() {
        return view('users.login');
    }

    /**
     * Handles login
     * 
     * @param LoginRequest $request
     * @return Response
     */
    public function postLogin(LoginRequest $request) {

        if (Auth::attempt(['username' => $request->input('username'), 'password' => $request->input('password')], $request->input('remember_me'))) {
            flash()->success(trans('users.welcome', ['username' => Auth::user()->username]));

            return redirect('/');
        } else {
            flash()->error(trans('users.login_failed'));

            return redirect()->back();
        }
    }

    /**
     * Gets the registration form
     * 
     * @return Response
     */
    public function getRegister() {
        return view('users.register');
    }

    /**
     * Handles user registration
     * 
     * @param RegisterRequest $request
     * @return Response
     */
    public function postRegister(RegisterRequest $request) {
        $user = new User([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'deleted_at' => date('Y-m-d H:i:s'),
            'confirm_token' => Hash::make(time() . env('APP_KEY'))
        ]);
        $user->save();

        $data = [
            'user_email' => $request->input('email'),
            'username' => $request->input('username'),
            'user_subject' => trans('users.successful_registration'),
            'admin_subject' => trans('users.new_user_registered'),
            'user_mail_data' => [
                'username' => $request->input('username'),
                'link' => URL::to('users/confirm') . '?token=' . $user->confirm_token
            ],
            'admin_mail_data' => [
                'username' => $request->input('username'),
                'email' => $request->input('email'),
                'created_at' => $user->created_at
            ]
        ];

        $mail_send = $this->sendMail($data, 'emails.register', 'emails.new_user');

        if ($mail_send) {
            flash()->success(trans('users.register_success'));

            return redirect('/');
        } else {
            $user->delete();
            flash()->error(trans('users.registration_failed'));

            return redirect()->back()->withInput($request->input());
        }
    }

    /**
     * Handles account confirmation
     * 
     * @param Request $request
     * @return Response
     */
    public function getConfirm(Request $request) {
        $user = User::where('confirm_token', $request->input('token'))->first();

        if ($user != null) {
            $data = $user->confirmRegistration();
            $data['user_subject'] = trans('users.user_account_confirmed');
            $data['admin_subject'] = 'Account confirmation';

            $this->sendMail($data, 'emails.user_confirmed', 'emails.account_confirmed');

            flash()->success(trans('users.account_confirmed'));

            return redirect('users/login');
        } elseif ($user == null) {
            flash()->error(trans('users.confirmation_failed'));

            return redirect('/');
        }
    }

    /**
     * Handle logout
     * 
     * @return Reponse
     */
    public function getLogout() {
        Auth::user()->last_seen = Cache::get('last_seen_' . Auth::user()->id);
        Cache::forget('last_seen_' . Auth::user()->id);

        Auth::logout();

        flash()->info(trans('users.logout_success'));

        return redirect('/');
    }

    /**
     * Renders profile page
     * 
     * @return Response
     */
    public function getProfile() {
        return view('users.profile');
    }

    /**
     * Handles password change
     * 
     * @param ChangePasswordRequest $request
     * @return Response
     */
    public function putChangePassword(ChangePasswordRequest $request) {
        // Put session active tab
        Session::put('profile_tab', 'password');

        if (Hash::check($request->input('old_password'), Auth::user()->password)) {
            Auth::user()->password = Hash::make($request->input('new_password'));
            Auth::user()->save();

            flash()->success(trans('users.password_changed'));

            return redirect()->back();
        } else {
            flash()->error(trans('users.old_password_wrong'));

            return redirect()->back();
        }
    }

    /**
     * Handles email changing
     * 
     * @param ChangeEmailRequest $request
     * @return Response
     */
    public function putChangeEmail(ChangeEmailRequest $request) {
        Session::put('profile_tab', 'email');

        Auth::user()->email = $request->input('email');
        Auth::user()->save();

        flash()->success(trans('users.email_changed'));

        User::flushCache(Auth::user());

        return redirect()->back();
    }

    /**
     * Renders reset password form
     * 
     * @return Response
     */
    public function getForgottenPassword() {
        return view('users.forgotten_password');
    }

    /**
     * Handles forgotten password token creation and email sending
     * 
     * @param ForgottenPasswordRequest $request
     * @return Response
     */
    public function postForgottenPassword(ForgottenPasswordRequest $request) {
        $check = User::where('email', $request->input('email'));

        if ($check) {
            // Generate token
            $token = Hash::make(env('APP_KEY') . microtime());

            PasswordReset::add($token, $request->input('email'));

            $data = [
                'token' => $token,
                'minutes' => Config::get('auth.password.expire')
            ];

            // Send email
            Mail::send('emails.password_reset', $data, function($msg) use ($request) {
                $msg->to($request->input('email'));
                $msg->from(Config::get('mail.from.address'), 'NoReply');
                $msg->subject(trans('users.reset_password_subject'));
            });

            // Redirect back
            flash()->success(trans('users.reset_password_email_sent'));

            return redirect()->back();
        } else {
            flash()->warning(trans('users.no_such_user'));

            return redirect()->back();
        }
    }

    /**
     * Renders reset password form
     * 
     * @param Request $request
     * @return Response
     */
    public function getPasswordReset(Request $request) {
        if ($request->input('token')) {
            $check = PasswordReset::where('token', $request->input('token'))->first();

            if ($check && strtotime($check->created_at) + (Config::get('auth.password.expire') * 60) >= strtotime('now')) {
                return view('users.password_reset', ['token' => $request->input('token')]);
            } else {
                flash()->warning(trans('users.token_expired'));

                return redirect('users/forgotten-password');
            }
        } else {
            flash()->warning(trans('users.invalid_token'));

            return redirect('users/forgotten-password');
        }
    }

    /**
     * Handles forgotten password changing
     * 
     * @param PasswordResetRequest $request
     * @return Response
     */
    public function putPasswordReset(PasswordResetRequest $request) {
        $check = PasswordReset::where(['email' => $request->input('email'), 'token' => $request->input('token')])->first();

        if ($check) {
            User::changePassword($request);
            PasswordReset::where('email', $request->input('email'))->delete();

            flash()->success(trans('users.password_changed'));

            return redirect('users/login');
        } else {
            flash()->error(trans('users.no_such_user'));

            return redirect()->back();
        }
    }

    public function postChangeAvatar(ChangeAvatarRequest $request) {
        Session::put('profile_tab', 'avatar');
        
        Auth::user()->changeAvatar($request);

        flash()->success(trans('users.avatar_changed'));

        return redirect()->back();
    }

    /**
     * Handles avatar deletion
     * 
     * @return Response
     */
    public function getDeleteAvatar() {
        Session::put('profile_tab', 'avatar');

        Auth::user()->deleteAvatar();

        flash()->success(trans('users.avatar_deleted'));

        return redirect()->back();
    }

}
