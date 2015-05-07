<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\AdminController;
// Models
use App\Models\Role;
// Requests
use App\Http\Requests\Admin\AddRoleRequest;
use App\Http\Requests\Admin\EditRoleRequest;
use App\Http\Requests\Role\SearchRequest;

class AdminRolesController extends AdminController {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Get all roles page
     * 
     * @param Request $request
     * @return Response
     */
    public function getAll(Request $request) {
        $query = new Role();

        return $this->all($request, $query, 'roles', 'admin/roles', trans('roles.all_title'), trans('roles.delete_message'), 'admin.roles.all');
    }

    /**
     * Render add form
     * 
     * @return Response
     */
    public function getAdd() {
        return view('admin.roles.add');
    }

    /**
     * Handles role add
     * 
     * @param AddRoleRequest $request
     * @return Response
     */
    public function postAdd(AddRoleRequest $request) {
        Role::create([
            'role' => $request->input('role')
        ]);

        flash()->success(trans('roles.role_added'));

        return redirect()->back();
    }

    /**
     * Handles delete
     * 
     * @param int $id Role id
     * @return Response
     */
    public function getDelete($id) {
        $role = Role::find($id);

        try {
            if ($role->role != 'admin') {
                $role->delete();
                $role->deleteSearchIndex();

                flash()->success(trans('roles.role_deleted'));

                return redirect()->back();
            } else {
                throw new \Exception('Can not delete!');
            }
        } catch (\Exception $e) {
            flash()->error(trans('roles.can_not_delete'));

            return redirect()->back();
        }
    }

    /**
     * Renders edit page
     * 
     * @param int $id Role id
     * @return Response
     */
    public function getEdit($id) {
        $role = Role::find($id);

        if ($role->role != 'admin') {
            return view('admin.roles.edit', ['role' => $role]);
        } else {
            flash()->error(trans('roles.can_not_edit'));

            return redirect()->back();
        }
    }

    /**
     * Handles role edit
     * 
     * @param EditRoleRequest $request
     * @param int $id Role id
     * @return Response
     */
    public function putEdit(EditRoleRequest $request, $id) {
        $role = Role::find($id);
        $role->role = $request->input('role');
        $role->save();
        
        flash()->success(trans('roles.role_edited'));

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

        $results = Role::search($this->search($search, 'roles'));

        $query = $results;
        $count = $query->count();

        // Order
        if ($param && $order) {
            $results = $results->orderBy($param, $order);
        } else {
            $param = null;
        }

        return view('admin.roles.search', [
            'results' => $results->get(),
            'param' => $param,
            'order' => $order,
            'search' => $search,
            'uri' => 'admin/roles',
            'count' => $count,
            'all' => Role::count(),
            'delete_message' => trans('roles.delete_message')
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
