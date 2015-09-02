<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Permission model
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class Permission extends Model
{
    use CacheTrait;

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
        $cache = self::getCacheInstance($cacheTags);

        if ($cache->has($cacheKey)) {
            return $cache->get($cacheKey);
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

            $cache->put($cacheKey, $permission, 60);

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
