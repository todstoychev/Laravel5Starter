<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Models\Action;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

/**
 * Permissions controller
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class AdminPermissionsController extends AdminController
{
    /**
     * @inheritdoc
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all page
     *
     * @return \Illuminate\View\View
     */
    public function getAll()
    {
        $actions = Action::with(['permissions', 'permissions.role'])
            ->get();
        $roles = Role::all();

        $data = [
            'title' => trans('permissions.all_title'),
            'actions' => $actions,
            'roles' => $roles,
            'uri' => 'admin/permissions',
            'search' => null
        ];

        return view('admin.permissions.all', $data);
    }

    /**
     * Handle permissions change
     *
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putAll(Request $request) {
        $permissions = Permission::all();
        $input = array_keys($request->input('permissions'));

        try {
            DB::beginTransaction();
            $permissions->each(function ($permission) use ($input) {
                if (in_array($permission->id, $input)) {
                    $permission->allow = true;
                } else {
                    $permission->allow = false;
                }
                $permission->save();
            });
            DB::commit();

            Cache::tags(['permissions'])->flush();

            flash()->success(trans('permissions.save_success'));
        } catch (\Exception $e) {
            flash()->error(trans('permissions.save_error'));
        }

        return redirect()->back();
    }

    /**
     * Handle search page
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function getSearch(Request $request)
    {
        $search = $request->input('search');
        $roles = Role::all();

        try {
            $actions = Action::search($this->search($search, ['proximity' => false, 'fuzzy' => 0.1, 'phrase' => false]));

            $data = [
                'actions' => $actions->get(),
                'roles' => $roles,
                'search' => $search,
                'uri' => 'admin/permissions',
                'title' => trans('temp.search_results'),
            ];

        } catch (\Exception $e) {
            $data = [
                'actions' => [],
                'roles' => $roles,
                'search' => $search,
                'uri' => 'admin/permissions',
                'title' => trans('temp.search_results'),
            ];
        }

        return view('admin.permissions.all', $data);
    }

    /**
     * Handle search form
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function postSearch(Request $request)
    {
        return $this->getSearch($request);
    }
}
