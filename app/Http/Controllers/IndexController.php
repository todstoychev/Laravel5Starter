<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class IndexController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        return view('index.index');
    }
    
    /**
     * Change locale
     * 
     * @param string $locale
     * @return Response
     */
    public function getChangeLocale($locale) {
        Session::put('locale', $locale);
        
        return redirect()->back();
    }
}
