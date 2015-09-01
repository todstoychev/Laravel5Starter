<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

/**
 * Permission model
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class Permission extends Model
{
    /**
     * Database table name
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Get permissions
     *
     * @param string $action
     * @param mixed $roleIds
     * @return mixed
     */
    public static function getPermission($action, $roleIds)
    {
        $cacheKey = md5($action . serialize($roleIds));
        $cacheTags = ['permissions'];

        if (Cache::tags($cacheTags)->has($cacheKey)) {
            return Cache::tags($cacheTags)->get($cacheKey);
        } else {
            $result = self::select('permissions.*')
                ->join('actions as actions', 'actions.id', '=', 'permissions.action_id', 'left')
                ->join('roles as roles', 'roles.id', '=', 'permissions.role_id', 'left')
                ->where(['actions.action' => $action, 'permissions.allow' => true]);

            if ($roleIds) {
                $result->whereIn('roles.id', $roleIds);
            } else {
                $result->where('roles.id', null);
            }

            $permission = $result->first();

            Cache::tags($cacheTags)->put($cacheKey, $permission, 60);

            return $permission;
        }
    }

    /**
     * Role relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function role()
    {
        return $this->belongsTo('App\Models\Role');
    }

    /**
     * Action relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function action()
    {
        return $this->belongsTo('App\Models\Action');
    }
}
