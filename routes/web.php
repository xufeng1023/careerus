<?php

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index');
Route::get('/search', 'LocationController@search');
Route::get('/jobs', 'PostController@all');
Route::get('/job/{postSlug}', 'PostController@show');

Route::post('/apply', 'ApplyController@save');
Route::post('/applyRegister', '\App\Http\Controllers\Auth\RegisterController@register');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/applies', 'UserController@applies');
    Route::get('/account', 'UserController@account');

    Route::post('/account', 'UserController@accountUpdate');
    Route::post('/password', 'UserController@passwordUpdate');
    Route::post('/resume', 'UserController@resumeUpdate');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/applies', 'ApplyController@all');
    Route::get('/jobs', 'PostController@allAdmin');
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
    Route::post('/applied/notify/{apply}', 'ApplyController@notify');
});