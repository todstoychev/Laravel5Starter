<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class AdminController extends Controller {
    
    public function __construct() {
        parent::__construct();
        $this->middleware('admin', []);
    }
    
    /**
     * Admin index page
     * 
     * @return Response
     */
    public function getIndex() {
        return view('admin.index.index');
    }

}
