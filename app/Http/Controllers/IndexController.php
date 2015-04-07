<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Requests\ImageRequest;
use Lib\ICR;
use Illuminate\Http\Request;

class IndexController extends Controller {
    
    public function __construct() {
        parent::__construct();
    }

    public function getIndex() {
        return view('index.index');
    }
}
