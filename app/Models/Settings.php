<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Settings extends Model {

    /**
     * Database table name
     *
     * @var String
     */
    protected $table = 'settings';

    /**
     * Timestamps
     *
     * @var Boolean
     */
    public $timestamps = false;

    /**
     * Fillable params
     *
     * @var Array
     */
    protected $fillable = ['param', 'value'];

    /**
     * Get all settings as array
     * 
     * @return Array
     */
    public static function getAll() {
        if (Cache::has('settings')) {
            $array = Cache::get('settings');
        } else {
            $query = self::all();
            $array = [];

            foreach ($query as $item) {
                $array[$item->param] = $item->value;
            }

            Cache::forever('settings', $array);
        }
        
        return $array;
    }
    
    /**
     * Get locales as array
     * 
     * @return Array
     */
    public static function getLocales() {
        $array = self::getAll();
        $value = $array['locales'];
        
        $locales = explode(', ', $value);
        
        return $locales;
    } 
    
    /**
     * Get the favicon
     * 
     * @return string
     */
    public static function getFavicon() {
        $array = self::getAll();
        $value = $array['favicon'];
        
        return $value;
    }

    /**
     * Gets the sitename
     * 
     * @return String
     */
    public static function getSitename() {
        $array = self::getAll();
        $value = $array['sitename_' . Session::get('locale')];
        
        return $value;
    }
    
    
}
