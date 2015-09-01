<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Session;

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
     * @return \Illuminate\Http\RedirectResponse
     */
    public function getChangeLocale($locale) {
        Session::put('locale', $locale);
        
        return redirect()->back();
    }
}
