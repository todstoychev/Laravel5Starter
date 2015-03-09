<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class IndexController extends Controller {

    public function getIndex() {
        return view('index.index');
    }

}
