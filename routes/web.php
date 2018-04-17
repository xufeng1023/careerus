<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/jobs', 'PostController@all');
Route::get('/job/{postSlug}', 'PostController@show');
Route::get('/apply', 'ApplyController@form');
//Route::post('/apply', 'ApplyController@save');

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/', 'PostController@allAdmin');
    Route::get('/user', 'UserController@all');
    Route::get('/catagory', 'CatagoryController@allAdmin');
    Route::get('/company', 'CompanyController@all');
    Route::post('/post/add', 'PostController@save');
    Route::post('/post/update/{post}', 'PostController@update');
    Route::post('/user/add', 'UserController@save');
    Route::post('/user/update/{user}', 'UserController@update');
    Route::post('/catagory/add', 'CatagoryController@save');
    Route::post('/company/add', 'CompanyController@save');
    Route::post('/company/update/{company}', 'CompanyController@update');
});