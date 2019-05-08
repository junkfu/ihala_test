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

Route::get('/', function () {
    return view('welcome');
});

Route::get('/cust_list', function () {
    return view('test');
});

Route::get('/customers/pages','Customers@pages');
Route::get('/customers/page/{page}','Customers@page');
Route::post('/customers/edit/{id}','Customers@edit');
Route::post('/customers/toCrm/{id}','Customers@toCrm');
