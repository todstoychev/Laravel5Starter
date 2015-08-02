<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

class IndexController extends Controller {
    
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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getChangeLocale($locale) {
        Session::put('locale', $locale);
        
        return redirect()->back();
    }
}
