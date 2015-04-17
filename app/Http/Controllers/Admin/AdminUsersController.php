<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\AdminController;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Lib\ICR;
// Models
use App\Models\User;
use App\Models\UserRole;
use App\Models\Role;
//Requests
use Illuminate\Http\Request;
use App\Http\Requests\Admin\AddUserRequest;
use App\Http\Requests\Admin\EditUserRequest;
use App\Http\Requests\User\SearchRequest;

class AdminUsersController extends AdminController {

    public function __construct() {
        parent::__construct();
        $this->middleware('force_https', [
            'only' => [
                'getAdd',
                'postAdd',
            ]
        ]);
    }

    /**
     * Renders all page
     * 
     * @param integer $limit Items per page
     * @param string $param Column to order
     * @param string $order Order direction
     * @return Response
     */
    public function getAll(Request $request) {
        $query = User::getAll(true, true);

        return $this->all($request, $query, 'users', 'admin/users', trans('users.all_title'), trans('users.delete_message'), 'admin.users.all');
    }

    /**
     * Deletes user
     * 
     * @param int $id User id
     * @return Reponse
     */
    public function getDelete($id) {
        $user = User::find($id);

        if (!$user->hasRole('admin') && count($user->getAdmins(false, true)) > 1) {
            UserRole::where('user_id', $id)->first()->delete();

            $user->forceDelete();
            $user->deleteSearchIndex();

            Cache::flush(['admin_users']);

            flash()->success(trans('users.delete_success'));

            return redirect()->back();
        } else {
            flash()->error(trans('users.can_not_delete'));

            return redirect()->back();
        }
    }

    /**
     * Renders add user form
     * 
     * @return Response
     */
    public function getAdd() {
        $roles = Role::all();

        return view('admin.users.add', ['roles' => $roles]);
    }

    /**
     * Adds an user
     * 
     * @param AddUserRequest $request
     * @return Response
     */
    public function postAdd(AddUserRequest $request) {
        $user = new User();

        $user->changeProfile($request);
        $user->roles()->attach($request->input('roles'));
        $user->changeSettings($request);

        if ($request->file('avatar')) {
            $user->changeAvatar($request);
        }

        flash()->success(trans('users.add_success'));

        return redirect()->back();
    }

    /**
     * Renders edit user page
     * 
     * @param int $id User id
     * @return Response
     */
    public function getEdit($id) {
        $user = User::getUser($id, true);
        $roles = Role::all();

        return view('admin.users.edit', [
            'user' => $user,
            'roles' => $roles
        ]);
    }

    /**
     * Handles user edit
     * 
     * @param EditUserRequest $request
     * @param int $id User id
     * @return Response
     */
    public function putEdit(EditUserRequest $request, $id) {
        $user = User::find($id);

        $user->changeProfile($request);
        $user->roles()->attach($request->input('roles'));
        $user->changeSettings($request);

        if ($request->file('avatar')) {
            $user->changeAvatar($request);
        }

        flash()->success(trans('users.edit_success'));

        return redirect()->back();
    }
    
    /**
     * Deletes user avatar
     * 
     * @param int $id User id
     * @return Response
     */
    public function getDeleteAvatar($id) {
        $user = User::find($id);
        $user->deleteAvatar();
        
        flash()->success(trans('users.avatar_deleted'));
        
        return redirect()->back();
    }

    /**
     * Disable user
     * 
     * @param int $id User id
     * @return Response
     */
    public function getDisable($id) {
        $user = User::find($id);

        if (!$user->hasRole('admin') && count($user->getAdmins(false, true)) > 1) {
            $user->delete();

            flash()->success(trans('users.disabled_success'));

            Cache::flush('admin_users');

            return redirect()->back();
        } else {
            flash()->error(trans('users.can_not_deactivate'));

            return redirect()->back();
        }
    }

    /**
     * Activates user
     * 
     * @param int $id User id
     * @return Response
     */
    public function getActivate($id) {
        $user = User::getUser($id, true);

        $user->deleted_at = null;
        $user->save();

        Cache::flush('admin_users');

        flash()->success(trans('users.activated_success'));

        return redirect()->back();
    }

    /**
     * Gets search page
     * 
     * @param Request $request
     * @return Response
     */
    public function getSearch(Request $request) {
        $order = $request->input('order');
        $param = $request->input('param');
        $search = $request->input('search');

        $results = User::search($this->search($search, 'users'));

        $query = $results;
        $count = $query->count();

        // Order
        if ($param && $order) {
            $results = $results->orderBy($param, $order);
        } else {
            $param = null;
        }

        return view('admin.users.search', [
            'results' => $results->get(),
            'param' => $param,
            'order' => $order,
            'search' => $search,
            'uri' => 'admin/users',
            'count' => $count,
            'all' => User::count(),
            'delete_message' => trans('users.delete_message')
        ]);
    }

    /**
     * Handles search
     * 
     * @param SearchRequest $request
     * @return Response
     */
    public function postSearch(SearchRequest $request) {
        return $this->getSearch($request, $request->input('search'), null, null);
    }

}
