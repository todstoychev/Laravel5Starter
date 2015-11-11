<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nqxcode\LuceneSearch\Model\SearchableInterface;
use Nqxcode\LuceneSearch\Model\SearchTrait;

/**
 * Role model
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class Role extends Model implements SearchableInterface {
    use SearchTrait,
        ChecksTrait;

    /**
     * Database table name
     *
     * @var String
     */
    protected $table = 'roles';

    /**
     * Timestamps
     *
     * @var Boolean
     */
    public $timestamps = false;

    /**
     * Fillable attributes
     *
     * @var Array
     */
    protected $fillable = ['role'];

    /**
     * Gets roles as array of objects
     * 
     * @param array $ids Roles ids
     * @return Array
     */
    public static function getRolesToArray($ids) {
        $roles = self::whereIn('id', $ids)->get();

        $array = [];

        foreach ($roles as $role) {
            $array[] = $role;
        }

        return $array;
    }

    /**
     * Handles search
     * 
     * @param array $search Search ids
     * @return self
     */
    public static function search($search) {
        $query = self::whereIn('id', $search);

        return $query;
    }

    /**
     * Users
     * 
     * @return object
     */
    public function users() {
        return $this->belongsToMany('App\Models\User', 'users_roles');
    }

    /**
     * Permission relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function permissions()
    {
        return $this->hasMany('App\Models\Permisson');
    }

    /**
     * Is the model available for search indexing?
     *
     * @return boolean
     */
    public function isSearchable()
    {
        return true;
    }

    /**
     * @inheritdoc
     */
    public static function searchableIds()
    {
        return self::all()->lists('id');
    }

}
