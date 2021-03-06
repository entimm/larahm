<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::match(['get', 'post'], '/', 'HmController@index')->name('index');

Route::match(['get', 'post'], '/callback', 'HmController@callback')->name('callback');

Route::match(['get', 'post'], env('ADMIN_ROUTE', '/admin'), 'HmController@admin')->name('admin');

Route::match(['get', 'post'], '/payments/{payment}', 'HmController@payment')->name('payment');
