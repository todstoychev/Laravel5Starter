<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Support\Facades\Hash;
use Mmanos\Search\Search;
use Illuminate\Support\Facades\Cache;
use Lib\ICR;
use Illuminate\Support\Facades\Config;

class User extends Model implements AuthenticatableContract {

    use \Illuminate\Database\Eloquent\SoftDeletes,
        Authenticatable;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['username', 'email', 'password', 'confirm_token', 'confirmed_at'];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password', 'remember_token'];

    /**
     * Base User query
     * 
     * @param boolean $with_trashed Get with deleted
     * @param boolean $with_join Use defined joins
     * @return Illuminate\Database\Eloquent\Model
     */
    private static function base($with_trashed = false, $with_join = false) {
        $query = self::select('users.*')
                ->with('roles');

        $with_trashed ? $query->withTrashed() : null;

        if ($with_join) {
            $query->leftJoin('user_roles as ur', 'ur.user_id', '=', 'users.id')
                    ->leftJoin('roles as r', 'r.id', '=', 'ur.role_id');
        }

        $query->distinct();

        return $query;
    }

    /**
     * Creates all elements query
     * 
     * @param Boolean $with_trashed With soft deleted entries
     * @param Boolean $with_join With join clauses
     * @return Illuminate\Database\Eloquent\Model
     */
    public static function getAll($with_trashed, $with_join) {
        $query = self::base($with_trashed, $with_join);

        return $query;
    }

    /**
     * Handles user registration
     * 
     * @param RegisterRequest $request
     */
    public static function register(RegisterRequest $request) {
        self::create([
            'username' => $request->input('username'),
            'password' => Hash::make($request->input('password')),
            'email' => $request->input('email'),
            'active' => false,
            'confirm_token' => Hash::make(time() . env('APP_KEY'))
        ]);
    }

    /**
     * Handles password changing on password reset
     * 
     * @param \App\Http\Requests\Request $request
     */
    public static function changePassword(\App\Http\Requests\User\PasswordResetRequest $request) {
        $user = self::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();
    }

    /**
     * Gets a single user
     * 
     * @param boolean $with_trashed Extract with soft deleted
     * @param boolean $with_joins Extract record with joins
     * @return self
     */
    public static function getUser($id, $with_trashed = false, $with_joins = false) {
        $user = self::base($with_trashed, $with_joins)
                ->where('id', $id)
                ->first();

        return $user;
    }

    /**
     * Handles search
     * 
     * @param Array $search
     * @return self
     */
    public static function search($search) {
        $query = self::base(true, true)
                ->whereIn('users.id', $search);

        return $query;
    }

    /**
     * Get all admin users
     * 
     * @param boolean $with_thrashed Get with soft deleted
     * @param boolean $with_join Add joins
     * @return Array
     */
    public static function getAdmins($with_thrashed, $with_join) {
        if (Cache::has('admin_users')) {
            return Cache::get('admin_users');
        } else {
            $query = self::base($with_thrashed, $with_join)
                    ->where('r.role', 'admin')
                    ->get();

            Cache::put('admin_users', $query, 60);

            return $query;
        }
    }
    
    /**
     * Flush the users cache
     * 
     * @param \App\Models\User $user
     */
    public static function flushCache(User $user = null) {
        if ($user && $user->hasRole('admin')) {
            Cache::flush(['admin_users']);
        }
    }

    /**
     * Handles user account confirmation
     * 
     * @param \App\User $user
     * @return Array
     */
    public function confirmRegistration() {
        $this->confirm_token = null;

        $this->confirmed_at = date('Y-m-d H:i:s');
        $this->save();

        $data = [
            'username' => $this->username,
            'user_email' => $this->email,
            'created_at' => $this->created_at,
            'confirmed_at' => $this->confirmed_at
        ];

        return $data;
    }

    /**
     * Get all roles list as string
     * 
     * @return String
     */
    public function getRoles() {
        $string = null;

        $last = end($this->roles);

        foreach ($this->roles as $role) {
            if ($role->role != end($last)->role) {
                $string .= $role->role . ', ';
            } else {
                $string .= $role->role;
            }
        }

        return $string;
    }

    /**
     * Check if user has role
     * 
     * @param string $check Role name
     * @return Boolean
     */
    public function hasRole($check) {
        return in_array($check, array_fetch($this->roles->toArray(), 'role'));
    }

    /**
     * Creates search index
     */
    public function addSearchIndex() {
        $search = new Search();

        $search->index($this->table)->insert($this->id, [
            'username' => $this->username,
            'email' => $this->email
        ]);
    }

    /**
     * Deletes search index entry
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
     * Get last seen
     * 
     * @return String
     */
    public function lastSeen() {
        if (Cache::has('last_seen_' . $this->id)) {
            return Cache::get('last_seen_' . $this->id);
        } elseif ($this->last_seen) {
            return $this->last_seen;
        } else {
            return null;
        }
    }

    /**
     * Change user profile
     * 
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    public function changeProfile(\Illuminate\Http\Request $request) {
        $this->username = $request->input('username');

        if ($request->input('password')) {
            $this->password = Hash::make($request->input('password'));
        }

        $this->email = $request->input('email');
        $this->save();
    }

    /**
     * Change settings
     * 
     * @param \Illuminate\Http\Request $request
     * @return \App\Models\User
     */
    public function changeSettings(\Illuminate\Http\Request $request) {
        if ($request->input('active')) {
            $this->confirmed_at = \Carbon\Carbon::now();
            $this->confirm_token = null;
        } else {
            $this->deleted_at = \Carbon\Carbon::now();
        }
        
        $this->save();
    }

    public function changeAvatar(\Illuminate\Http\Request $request) {
        if ($this->avatar) {
            $this->deleteAvatar();
        }

        $icr = new ICR('avatar', $request->file('avatar'));

        $this->avatar = $icr->getFilename();
        $this->save();
    }

    /**
     * Deletes avatar
     * 
     * @return \App\Models\User
     */
    public function deleteAvatar() {
        $config = Config::get('image_crop_resizer');
        $path = public_path($config['uploads_path']);

        unlink($path . '/avatar/' . $this->avatar);

        foreach ($config['avatar'] as $size => $values) {
            unlink($path . '/avatar/' . $size . '/' . $this->avatar);
        }

        $this->avatar = null;
        $this->save();
    }
    
    /**
     * Users roles
     * 
     * @return object
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'user_roles');
    }

}
