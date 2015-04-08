<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;

class IndexController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        return view('index.index');
    }
}
