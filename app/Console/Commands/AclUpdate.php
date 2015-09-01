<?php

namespace App\Console\Commands;

use App\Models\Action;
use App\Models\Role;
use Illuminate\Support\Facades\Cache;

/**
 * AclUpdate command used to update the permissions table. It will add
 * a newly created routes to the permissions table and will delete a
 * non existing once.
 *
 * @author Todor Todorov
 * @package App\Console\Commands
 */
class AclUpdate extends AclClear
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'acl:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Removes unused/non existing actions and adds new once.';

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
        $permissions = $this->getAllActionActions();
        $actions = $this->getRouteData();
        $roles = Role::all();

        // Add new actions
        foreach ($actions as $action => $uri) {
            if (!array_key_exists($action, $permissions)) {
                $newAction = new Action([
                    'action' => $action,
                    'uri' => $uri
                ]);
                $newAction->save();

                $this->savePermissions($roles, $newAction);

                $this->info("Added " . $action . "\n");
            } else {
                unset($permissions[$action]);
            }
        }

        // Remove non existing actions
        foreach ($permissions as $action => $uri) {
            Action::where([
                'action' => $action,
                'uri' => $uri
            ])->first()
                ->destroy();
            $this->comment("Removed " . $action . "\n");
        }

        Cache::tags(['permissions'])->flush();

        $this->info("Done. \n");
    }

    /**
     * Gets all existing database records for permissions and add them
     * in array, according to the routes array from the getRouteData method
     *
     * @return array
     */
    protected function getAllActionActions()
    {
        $array = [];
        $permissions = Action::all();

        foreach ($permissions as $permission) {
            $array[$permission->action] = $permission->uri;
        }

        return $array;
    }
}
