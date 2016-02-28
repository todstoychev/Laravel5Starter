<?php

namespace App\Http\Controllers;

use App\Models\Settings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\URL;

/**
 * Index page controller, holds the language switching method
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Http\Controllers
 */
class IndexController extends Controller {

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
        return view('index.index');
    }

    /**
     * Change locale
     *
     * @param string $locale
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getChangeLocale($locale) {
        $previous = URL::previous();
        $serverName = url();
        $path = str_replace($serverName . '/', '',$previous);

        if ($path !== '/') {
            $segments = explode('/', $path);
        } else {
            $segments = [];
        }

        $locales = Settings::getLocales();
        $fallBackLocale = Settings::get('fallback_locale');

        if (!in_array($locale, $locales)) {
            $locale = $fallBackLocale;
        }

        app()->setLocale($locale);
        Session::put('locale', $locale);

        if (array_key_exists(0, $segments) && preg_match('/[A-z]{2}/', $segments[0])) {
            $segments[0] = $locale;
        } else {
            array_unshift($segments, $locale);
        }

        return redirect()->to(implode('/', $segments));
    }
}
