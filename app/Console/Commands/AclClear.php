<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\ActionRoles;
use App\Models\CacheTrait;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Console\Command;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Route;

/**
 * Command will rebuild the permissions table. This will cause all routes
 * and actions to become public. Be aware when running this command on
 * production. It will destroy all your permissions data.
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Console\Commands
 */
class AclClear extends Command
{
    use CacheTrait;

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear the access control list.';

    /**
     * Create a new command instance.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->info("Clearing permissions... \n");

        // Delete old data
        $response = Action::deleteAllData();

        (null !== $response) ? $this->error("\n" . $response . "\n") : null;

        $this->info("Permissions cleared... \n");

        try {
            $routeData = $this->getRouteData();
            $roles = Role::all();
            DB::beginTransaction();
            foreach ($routeData as $action => $uri) {
                $action = new Action([
                    'uri' => $uri,
                    'action' => $action
                ]);
                $action->save();

                $this->savePermissions($roles, $action);

                $this->comment("Added action " . $action->action . "\n");
            }

            $cache = $this->getCacheInstance(['permissions']);
            $cache->flush();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error("\n" . $e->getMessage() . "\n");
        }

    }

    /**
     * Gets the route data
     *
     * @return array
     */
    protected function getRouteData()
    {
        $allRoutes = Route::getRoutes();

        $routeData = [];

        foreach ($allRoutes as $route) {
            if (!preg_match('/_debugbar/', $route->getUri())) {
                $uri = preg_replace('#/{[a-z\?]+}|{_missing}|/{_missing}#', '', $route->getUri());
                $routeData[$route->getActionName()] = $uri;
            }
        }

        return $routeData;
    }

    /**
     * Save permissions per action
     *
     * @param Collection $roles
     * @param Action $action
     */
    protected function savePermissions(Collection $roles, Action $action)
    {
        $roles->each(function($role) use ($action) {
            $permission = new Permission();
            $permission->role()->associate($role);
            $permission->action()->associate($action);
            $permission->save();
        });

        $permission = new Permission();
        $permission->action()->associate($action);
        $permission->save();
    }
}