<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
// Requests
use App\Http\Requests\Admin\Settings\LocalesRequest;
use App\Http\Requests\Admin\Settings\SitenameRequest;
use App\Http\Requests\Admin\Settings\FallbackLocaleRequest;
use App\Http\Requests\Admin\Settings\FaviconRequest;
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

    /**
     * Changes the favicon
     * 
     * @param FaviconRequest $request
     * @return Response
     */
    public function putFavicon(FaviconRequest $request) {
        Session::put('settings_tab', 'favicon');

        if ($request->file('favicon')->getMimeType() == 'image/x-icon') {
            $path = public_path(Settings::getFavicon());
            (file_exists($path) && !is_dir($path)) ? unlink($path) : null;
            $request->file('favicon')->move(public_path(), $request->file('favicon')->getClientOriginalName());
            Settings::where('param', 'favicon')->update(['value' => $request->file('favicon')->getClientOriginalName()]);

            Cache::flush('settings');

            flash()->success(trans('settings.favicon_changed'));

            return redirect()->back();
        } else {
            flash()->error(trans('settings.favicon_error'));

            return redirect()->back();
        }
    }

    /**
     * Deletes favicon
     * 
     * @return Response
     */
    public function getDeleteFavicon() {
        Session::put('settings_tab', 'favicon');
        
        $path = public_path(Settings::getFavicon());
        file_exists($path) ? unlink($path) : null;
        
        Settings::where('param', 'favicon')->update(['value' => null]);
        
        Cache::flush('settings');
        
        flash()->success(trans('settings.favicon_deleted'));
        
        return redirect()->back();
    }

}
