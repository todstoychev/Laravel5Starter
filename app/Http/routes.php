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
Route::group(['prefix' => 'admin', 'namespace' => 'Admin'], function () {
    Route::controller('slider', 'AdminSliderController');

    Route::controller('product-image', 'AdminProductImageController');
    
    Route::controller('products', 'AdminProductController');

    Route::controller('permissions', 'AdminPermissionsController');

    Route::controller('settings', 'AdminSettingsController');

    Route::controller('roles', 'AdminRolesController');

    Route::controller('users', 'AdminUsersController');

    Route::controller('/', 'AdminController');
});

// Public and user routes
Route::controller('contacts', 'ContactsController');

Route::controller('users', 'UsersController');

Route::controller('/', 'IndexController');