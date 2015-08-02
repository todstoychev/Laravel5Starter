<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Nqxcode\LuceneSearch\Model\Searchable;
use Nqxcode\LuceneSearch\Model\SearchTrait;

class Role extends Model implements Searchable {
    use SearchTrait;

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
     * Checks if role exists on edit
     *
     * @param int $id
     * @param $role
     * @return bool
     * @internal param string $name
     */
    public static function checkRoleOnEdit($id, $role) {
        $query = self::where('id', '!=', $id)
                ->where('role', '=',  $role)
                ->get();
        
        if (count($query) > 0) {
            return true;
        } else {
            return false;
        }
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
     * Is the model available for search indexing?
     *
     * @return boolean
     */
    public function isSearchable()
    {
        return true;
    }
}
