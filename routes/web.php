<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of the routes that are handled
| by your application. Just tell Laravel the URIs it should respond
| to using a Closure or controller method. Build something great!
|
*/

Route::get('/', 'DashboardController@home')->name('home');

Route::get('dashboard', 'DashboardController@stats')->name('stats');

Route::get('import', 'DashboardController@import')->name('import');
Route::post('import', 'DashboardController@processImportMariage')->name('processImportMariage');
