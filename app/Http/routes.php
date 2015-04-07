<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

// Admin routes
Route::controller('admin/roles', 'Admin\AdminRolesController');

Route::controller('admin/users', 'Admin\AdminUsersController');

Route::controller('admin', 'Admin\AdminController');

// Public and user routes
Route::controller('users', 'UsersController');

Route::controller('/', 'IndexController');