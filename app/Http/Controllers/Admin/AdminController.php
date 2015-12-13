<?php namespace App\Http\Controllers\Admin;

use App\Http\Requests;
use App\Http\Controllers\Controller;

/**
 * Base admin section controller
 *
 * @author Todor Todorov <todstoychev@gmail.com>
 * @package App\Http\Controllers\Admin
 */
class AdminController extends Controller {

    /**
     * @inheritdoc
     */
    public function __construct() {
        parent::__construct();
    }

    /**
     * Admin index page
     *
     * @return \Illuminate\View\View
     */
    public function getIndex() {
        return view('admin.index.index');
    }
    
    /**
     * Missing method handler
     * 
     * @param array $parameters
     * @return \Illuminate\View\View
     */
    public function missingMethod($parameters = array())
    {
        return view('errors.404', ['admin' => 'admin.']);
    }

}
