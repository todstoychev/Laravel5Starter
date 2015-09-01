<?php

namespace App\Models;

use App\Http\Requests\User\PasswordResetRequest;
use Carbon\Carbon;
use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Lib\ICR;
use Illuminate\Support\Facades\Config;
use Nqxcode\LuceneSearch\Model\Searchable;
use Nqxcode\LuceneSearch\Model\SearchTrait;

/**
 * User model
 *
 * @property int id
 * @property null|string confirm_token
 * @property string username
 * @property string password
 * @property \DateTime confirmed_at
 * @property \DateTime last_seen
 * @property string avatar
 * @property Role roles
 * @property null|\DateTime deleted_at
 * @property string email
 * @property \DateTime created_at
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Models
 */
class User extends Model implements AuthenticatableContract, Searchable {

    use SoftDeletes,
        Authenticatable,
        SearchTrait,
        RolesTrait;

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
     * @param boolean $withTrashed Get with deleted
     * @param boolean $withJoin Use defined joins
     * @return Model
     */
    private static function base($withTrashed = false, $withJoin = false) {
        $query = self::select('users.*')
                ->with('roles');

        $withTrashed ? $query->withTrashed() : null;

        if ($withJoin) {
            $query->leftJoin('user_roles as ur', 'ur.user_id', '=', 'users.id')
                    ->leftJoin('roles as r', 'r.id', '=', 'ur.role_id');
        }

        $query->distinct();

        return $query;
    }

    /**
     * Creates all elements query
     * 
     * @param Boolean $withTrashed With soft deleted entries
     * @param Boolean $withJoin With join clauses
     * @return Model
     */
    public static function getAll($withTrashed, $withJoin) {
        $query = self::base($withTrashed, $withJoin);

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
     * @param \App\Http\Requests\Request|PasswordResetRequest $request
     */
    public static function changePassword(PasswordResetRequest $request) {
        $user = self::where('email', $request->input('email'))->first();
        $user->password = Hash::make($request->input('password'));
        $user->save();
    }

    /**
     * Gets a single user
     *
     * @param int $id User id
     * @param boolean $withTrashed Extract with soft deleted
     * @param boolean $withJoins Extract record with joins
     * @return User
     */
    public static function getUser($id, $withTrashed = false, $withJoins = false) {
        $user = self::base($withTrashed, $withJoins)
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
     * @param boolean $withTrashed Add trashed elements
     * @param boolean $withJoin Add joins
     * @return Array
     * @internal param bool $with_thrashed Get with soft deleted
     */
    public static function getAdmins($withTrashed, $withJoin) {
        if (Cache::has('admin_users')) {
            return Cache::get('admin_users');
        } else {
            $query = self::base($withTrashed, $withJoin)
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
     * Get optional search attributes
     *
     * @return array
     */
    public function getOptionalAttributesAttribute()
    {
        return [
            'confirm_token' => $this->confirm_token
        ];
    }

    /**
     * Handles user account confirmation
     *
     * @return Array
     * @internal param \App\Models\User $user
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
     * @param Request $request
     */
    public function changeProfile(Request $request) {
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
     * @param Request $request
     */
    public function changeSettings(Request $request) {
        if ($request->input('active')) {
            $this->confirmed_at = Carbon::now();
            $this->confirm_token = null;
        } else {
            $this->deleted_at = Carbon::now();
        }
        
        $this->save();
    }

    public function changeAvatar(Request $request) {
        if ($this->avatar) {
            $this->deleteAvatar();
        }

        $icr = new ICR('avatar', $request->file('avatar'));

        $this->avatar = $icr->getFilename();
        $this->save();
    }

    /**
     * Deletes avatar
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
     * Roles relation
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function roles() {
        return $this->belongsToMany('App\Models\Role', 'user_roles');
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
