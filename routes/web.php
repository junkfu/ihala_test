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

Route::get('cust_list', function () {
    return view('cust_list');
});

Route::get('action_list', function () {
    return view('action_list');
});

Route::get('/test_view', function () {
    return view('test');
});

//Customer
Route::get('/customers/pages','Customers@pages');
Route::get('/customers/page/{page}','Customers@page');
Route::post('/customers/edit/{id}','Customers@edit');
Route::post('/customers/insertBySuper8','Customers@insertBySuper8');
Route::post('/customers/updateBySuper8','Customers@updateBySuper8');
Route::post('/customers/insertCrm','Customers@insertCrm');

//CRM
Route::post('/customers/addCrm','Customers@addCrm');

//Action
Route::get('/action/pages','Action@pages');
Route::get('/action/page/{page}','Action@page');
Route::post('/action/updateCrmByEmployee/','Action@updateCrmByEmployee');
//Route::post('/action/edit/{id}','Customers@edit');
//Route::post('/action/insert','Customers@insert');


Route::get('/customers/test','Customers@test');
Route::post('/customers/test2','Customers@test2');
Route::get('/test2', 'Customers@test2');