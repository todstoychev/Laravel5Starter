<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
// Requests
use App\Http\Requests\Admin\Settings\LocalesRequest;
use App\Http\Requests\Admin\Settings\SitenameRequest;
use App\Http\Requests\Admin\Settings\FallbackLocaleRequest;
// Models
use App\Models\Settings;

class AdminSettingsController extends Controller {

    public function __construct() {
        parent::__construct();
    }

    /**
     * Renders index page for the settings
     * 
     * @return Response
     */
    public function getIndex() {
        $settings = Settings::getAll();
        $locales = Settings::getLocales();
        
        return view('admin.settings.index', [
            'settings' => $settings,
            'locales' => $locales,
            'settings_tab' => Session::pull('settings_tab')
        ]);
    }

    /**
     * Handles locales update
     * 
     * @param LocalesRequest $request
     * @return Response
     */
    public function putLocales(LocalesRequest $request) {
        Settings::where('param', 'locales')->update(['value' => $request->input('locales')]);

        Cache::flush('settings');
        
        Session::put('settings_tab', 'locales');

        flash()->success(trans('settings.locales_updated'));

        return redirect()->back();
    }
    
    /**
     * Handles sitename update
     * 
     * @param SitenameRequest $request
     * @return Response
     */
    public function putSitename(SitenameRequest $request) {
        foreach (Settings::getLocales() as $locale) {
            $sitename = Settings::where('param', 'sitename_' . $locale)->first();
            
            if ($sitename) {
                $sitename->update(['value' => $request->input('sitename_' . $locale)]);
            } else {
                Settings::create([
                    'param' => 'sitename_' . $locale,
                    'value' => $request->input('sitename_' . $locale)
                ]);
            }
        }
        
        Cache::flush('settings');
        
        Session::put('settings_tab', 'sitename');
        
        flash()->success(trans('settings.sitename_updated'));
        
        return redirect()->back();
    }
    
    /**
     * Handle fallback locale change
     * 
     * @param FallbackLocaleRequest $request
     * @return Response
     */
    public function putFallbackLocale(FallbackLocaleRequest $request) {
        Settings::where('param', 'fallback_locale')->update(['value' => $request->input('fallback_locale')]);
        
        Cache::flush('settings');
        
        Session::put('settings_tab', 'fallback-locale');
        
        flash()->success(trans('settings.fallback_locale_updated'));
        
        return redirect()->back();
    }

}
