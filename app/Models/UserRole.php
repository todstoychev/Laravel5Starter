<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model {
    
    /**
     * Database table name
     *
     * @var String
     */
    protected $table = 'user_roles';
    
    /**
     * Timestamps
     *
     * @var Boolean
     */
    public $timestamps = false;
    
    /**
     * Clean up user roles
     * 
     * @param int $user_id User id
     */
    public static function cleanUpRoles($user_id) {
        self::where('user_id', $user_id)->delete();
    }
}
