<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Mmanos\Search\Search;

class Role extends Model {

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
     * Add search index
     */
    public function addSearchIndex() {
        $search = new Search();
        $search->index($this->table)->insert($this->id, [
            'role' => $this->role
        ]);
    }

    /**
     * Deletes search index
     */
    public function deleteSearchIndex() {
        $search = new Search();
        $search->index($this->table)->delete($this->id);
    }

    /**
     * Update search index
     */
    public function updateSearchIndex() {
        $this->deleteSearchIndex();
        $this->addSearchIndex();
    }

    /**
     * Users
     * 
     * @return object
     */
    public function users() {
        return $this->belongsToMany('App\Models\User', 'users_roles');
    }

}
