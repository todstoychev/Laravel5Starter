<?php

namespace App\Http\Controllers\Admin;

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
     * @param Request $request
     * @internal param int $limit Items per page
     * @internal param string $param Column to order
     * @internal param string $order Order direction
     * @return \Illuminate\View\View
     */
    public function getAll(Request $request) {
        $query = User::getAll(true, true);

        return $this->all($request, $query, 'admin/users', trans('users.all_title'), trans('users.delete_message'), 'admin.users.all');
    }

    /**
     * Deletes user
     *
     * @param int $id User id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDelete($id) {
        $user = User::where('id', $id)->withTrashed()->first();

        if ($user->hasRole('admin') && count($user->getAdmins(false, true)) <= 1) {
            flash()->error(trans('users.can_not_delete'));

            return redirect()->back();
        } else {
            UserRole::where('user_id', $id)->delete();

            User::flushCache($user);

            flash()->success(trans('users.delete_success'));

            return redirect()->back();
        }
    }

    /**
     * Renders add user form
     *
     * @return \Illuminate\View\View
     */
    public function getAdd() {
        $roles = Role::all();

        return view('admin.users.add', ['roles' => $roles]);
    }

    /**
     * Adds an user
     *
     * @param AddUserRequest $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function postAdd(AddUserRequest $request) {
        $user = new User();

        $user->changeProfile($request);
        $user->roles()->attach($request->input('roles'));
        $user->changeSettings($request);

        if ($request->file('avatar')) {
            $user->changeAvatar($request);
        }
        
        $user->save();

        User::flushCache($user);

        flash()->success(trans('users.add_success'));

        return redirect()->back();
    }

    /**
     * Renders edit user page
     *
     * @param int $id User id
     * @return \Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putEdit(EditUserRequest $request, $id) {
        $user = User::find($id);
        $user->changeProfile($request);

        if ($user->hasRole('admin') && count($user->getAdmins(false, true)) <= 1 && (!in_array(1, $request->input('roles')) || !$request->input('active'))) {
            flash()->error(trans('users.can_not_edit'));

            return redirect()->back();
        } else {
            UserRole::where('user_id', $user->id)->delete();
            $user->roles()->attach($request->input('roles'));
            $user->changeSettings($request);
        }

        if ($request->file('avatar')) {
            $user->changeAvatar($request);
        }

        $user->save();

        User::flushCache($user);

        flash()->success(trans('users.edit_success'));

        return redirect()->back();
    }

    /**
     * Deletes user avatar
     *
     * @param int $id User id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDeleteAvatar($id) {
        $user = User::find($id);
        $user->deleteAvatar();
        $user->save();

        flash()->success(trans('users.avatar_deleted'));
        User::flushCache($user);

        return redirect()->back();
    }

    /**
     * Disable user
     *
     * @param int $id User id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getDisable($id) {
        $user = User::find($id);

        if ($user->hasRole('admin') && count($user->getAdmins(false, true)) <= 1) {
            flash()->error(trans('users.can_not_deactivate'));

            return redirect()->back();
        } else {
            $user->delete();

            flash()->success(trans('users.disabled_success'));

            User::flushCache($user);

            return redirect()->back();
        }
    }

    /**
     * Activates user
     *
     * @param int $id User id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getActivate($id) {
        $user = User::getUser($id, true);

        $user->deleted_at = null;
        $user->save();

        User::flushCache($user);

        flash()->success(trans('users.activated_success'));

        return redirect()->back();
    }

    /**
     * Gets search page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getSearch(Request $request) {
        $order = $request->input('order');
        $param = $request->input('param');
        $search = $request->input('search');

        try {
            $results = User::search($this->search($search, ['proximity' => false, 'fuzzy' => 0.1, 'phrase' => false]));

            $query = $results;
            $count = $query->count();

            // Order
            if ($param && $order) {
                $results = $results->orderBy($param, $order);
            } else {
                $param = null;
            }

            $data = [
                'results' => $results->get(),
                'param' => $param,
                'order' => $order,
                'search' => $search,
                'uri' => 'admin/users',
                'count' => $count,
                'all' => User::count(),
                'delete_message' => trans('users.delete_message')
            ];
        }
        catch (\Exception $e) {
            $data = [
                'results' => [],
                'param' => null,
                'order' => null,
                'search' => $search,
                'uri' => 'admin/users',
                'count' => 0,
                'all' => 0,
                'delete_message' => trans('users.delete_message')
            ];
        }
        return view('admin.users.search', $data);
    }

    /**
     * Handles search
     *
     * @param SearchRequest $request
     * @return \Illuminate\View\View
     */
    public function postSearch(SearchRequest $request) {
        return $this->getSearch($request, $request->input('search'), null, null);
    }

}
