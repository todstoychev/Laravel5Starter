<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;
// Models
use App\Models\Role;
// Requests
use App\Http\Requests\Admin\AddRoleRequest;
use App\Http\Requests\Admin\EditRoleRequest;
use App\Http\Requests\Role\SearchRequest;

class AdminRolesController extends AdminController
{

    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all roles page
     *
     * @param Request $request
     * @return View
     */
    public function getAll(Request $request)
    {
        $query = new Role();

        return $this->all($request, $query, 'admin/roles', trans('roles.all_title'), trans('roles.delete_message'), 'admin.roles.all');
    }

    /**
     * Render Add role page
     *
     * @return View
     */
    public function getAdd()
    {
        return view('admin.roles.add');
    }

    /**
     * Handles role creating
     *
     * @param AddRoleRequest $request
     * @return RedirectResponse
     */
    public function postAdd(AddRoleRequest $request)
    {
        Role::create([
            'role' => $request->input('role')
        ]);

        flash()->success(trans('roles.role_added'));

        return redirect()->back();
    }

    /**
     * Delete role
     *
     * @param int $id Role id
     * @return RedirectResponse
     */
    public function getDelete($id)
    {
        $role = Role::find($id);

        try {
            if ($role->role != 'admin') {
                $role->delete();

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
     * Get edit role page
     *
     * @param $id
     * @return RedirectResponse|View
     */
    public function getEdit($id)
    {
        $role = Role::find($id);

        if ($role->role != 'admin') {
            return view('admin.roles.edit', ['role' => $role]);
        } else {
            flash()->error(trans('roles.can_not_edit'));

            return redirect()->back();
        }
    }

    /**
     * Handles role update
     *
     * @param EditRoleRequest $request
     * @param int $id Role id
     * @return RedirectResponse
     */
    public function putEdit(EditRoleRequest $request, $id)
    {
        $role = Role::find($id);
        $check = Role::checkRoleOnEdit($role->id, $request->input('role'));

        if (!$check) {
            $role->role = $request->input('role');
            $role->save();

            flash()->success(trans('roles.role_edited'));
        } else {
            flash()->error(trans('roles.role_exists'));
        }
        return redirect()->back();
    }

    /**
     * Gets search page
     *
     * @param Request $request
     * @return View
     */
    public function getSearch(Request $request)
    {
        $order = $request->input('order');
        $param = $request->input('param');
        $search = $request->input('search');

        try {
            $results = Role::search($this->search($search, ['proximity' => false, 'fuzzy' => 0.1, 'phrase' => false]));

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
                'uri' => 'admin/roles',
                'count' => $count,
                'all' => Role::count(),
                'delete_message' => trans('roles.delete_message')
            ];

        } catch (\Exception $e) {
            $data = [
                'results' => [],
                'param' => null,
                'order' => null,
                'search' => $search,
                'uri' => 'admin/roles',
                'count' => 0,
                'all' => 0,
                'delete_message' => trans('roles.delete_message')
            ];

        }

        return view('admin.roles.search', $data);
    }

    /**
     * Handles search
     *
     * @param SearchRequest $request
     * @return View
     */
    public function postSearch(SearchRequest $request)
    {
        return $this->getSearch($request);
    }

}
