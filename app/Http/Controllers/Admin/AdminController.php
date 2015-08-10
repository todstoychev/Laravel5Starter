<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class AdminController extends Controller {

    /**
     * @inheritdoc
     */
    public function __construct() {
        parent::__construct();
        $this->middleware('admin');
    }

    /**
     * Admin index page
     *
     * @return \Illuminate\View\View
     */
    public function getIndex() {
        return view('admin.index.index');
    }

}
