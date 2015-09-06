<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
// Requests
use App\Http\Requests\Admin\Settings\LocalesRequest;
use App\Http\Requests\Admin\Settings\SitenameRequest;
use App\Http\Requests\Admin\Settings\FallbackLocaleRequest;
use App\Http\Requests\Admin\Settings\FaviconRequest;
// Models
use App\Models\Settings;
use Symfony\Component\HttpFoundation\Tests\RequestContentProxy;

/**
 * Controller that handles the settings CRUD in the admin part
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class AdminSettingsController extends AdminController {

    /**
     * @inheritdoc
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Get index page
     *
     * @return \Illuminate\View\View
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putLocales(LocalesRequest $request) {
        $fallback_locale = Settings::getFallBackLocale();
        Session::put('settings_tab', 'locales');
        
        // Check if try to set fallback locale that is not in the locales list
        if (!preg_match('/' . $fallback_locale . '/', $request->get('locales'))) {
            flash()->warning(trans('settings.fallback_locale_not_in_list'));
        } else {
            flash()->success(trans('settings.locales_updated'));
        }
                
        Settings::where('param', 'locales')->update(['value' => $request->input('locales')]);
        Cache::flush('settings');
        Session::put('locale', $fallback_locale);

        return redirect()->back();
    }

    /**
     * Handles sitename update
     *
     * @param SitenameRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function putFallbackLocale(FallbackLocaleRequest $request) {
        Settings::where('param', 'fallback_locale')->update(['value' => $request->input('fallback_locale')]);

        Cache::flush('settings');

        Session::put('settings_tab', 'fallback-locale');
        
        // Check if try to set fallback locale that is not in the locales list
        if (!in_array($request->input('fallback_locale'), Settings::getLocales())) {
            flash()->warning(trans('settings.fallback_locale_not_in_list'));
        } else {
            flash()->success(trans('settings.fallback_locale_updated'));
        }

        return redirect()->back();
    }

    /**
     * Changes the favicon
     *
     * @param FaviconRequest $request
     * @return \Illuminate\Http\RedirectResponse
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
     * Delete favicon
     *
     * @return \Illuminate\Http\RedirectResponse
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

    public function putContacts(Request $request)
    {
        Session::put('settings_tab', 'contacts');

        Settings::where('param', 'show_contacts_page')->update(['value' => ($request->input('show_contacts_page')) ? true : null]);

        Cache::flush('settings');

        flash()->success(trans('settings.contacts_updated'));

        return redirect()->back();
    }

}
