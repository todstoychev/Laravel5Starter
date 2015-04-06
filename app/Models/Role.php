<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

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
     * Gets roles as array of objects
     * 
     * @param array $ids Roles ids
     * @return Array
     */
    public static function getRolesToArray($ids) {
        $roles = self::whereIn('id', $ids)->get();
        
        $array = [];
        
        foreach($roles as $role) {
            $array[] = $role;
        }
        
        return $array;
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
