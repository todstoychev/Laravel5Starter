<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * PasswordReset model
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class PasswordReset extends Model {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'password_resets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['email', 'token', 'created_at'];
    
    /**
     * Timestamps
     *
     * @var Boolean
     */
    public $timestamps = false;
    
    /**
     * Deletes the old token if any. And creates new one.
     * 
     * @param string $token Reset token
     * @param string $email User email
     */
    public static function add($token, $email) {
        self::where('email', $email)->delete();
        
        self::create([
            'token' => $token,
            'email' => $email,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

}
