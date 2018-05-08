<?php

Auth::routes();

Route::get('/', 'PostController@index');

Route::get('/searchLocation', function(){
    $posts = App\Post::where('location', 'LIKE', '%'.request('s').'%')->take(5)->get()->unique('location');
    return $posts->pluck('location');
});

Route::get('/searchJob', function(){
    $posts = App\Post::where('title', 'LIKE', '%'.request('s').'%')->take(5)->get()->unique('title');
    return $posts->pluck('title');
});

Route::get('/jobs', 'PostController@all');
Route::get('/job/{postSlug}', 'PostController@show');
Route::get('/blog', 'BlogController@all');
Route::get('/blog/{blogSlug}', 'BlogController@show');

Route::post('/apply', 'ApplyController@save');
Route::post('/applyRegister', '\App\Http\Controllers\Auth\RegisterController@register');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/applies', 'UserController@applies');
    Route::get('/account', 'UserController@account');
    Route::get('/resume/download', 'UserController@resumeDownload');
    //Route::get('/payment', 'UserController@payment');
    //Route::get('/payment/invoices', 'UserController@invoices');
    //Route::get('/payment/invoice/{invoice}', 'UserController@invoice');

    Route::post('/account', 'UserController@accountUpdate');
    Route::post('/password', 'UserController@passwordUpdate');
    Route::post('/resume', 'UserController@resumeUpdate');
   // Route::post('/card', 'UserController@updateCardInfo');
   // Route::post('/buy', 'UserController@buy');
});

Route::prefix('admin')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/applies', 'ApplyController@all');
    Route::get('/jobs', 'PostController@allAdmin');
    Route::get('/user', 'UserController@all');
    Route::get('/blog', 'BlogController@allAdmin');
    Route::get('/catagory', 'CatagoryController@allAdmin');
    Route::get('/company', 'CompanyController@all');
    Route::get('/plan', 'PlanController@index');
    Route::get('/cover-letter', 'CoverLetterController@all');
    // Route::get('/settings', 'SettingsController@index');

    Route::post('/post/add', 'PostController@save');
    Route::post('/post/update/{post}', 'PostController@update');
    Route::post('/blog/add', 'BlogController@save');
    Route::post('/blog/update/{blog}', 'BlogController@update');
    Route::post('/user/add', 'UserController@save');
    Route::post('/user/update/{user}', 'UserController@update');
    Route::post('/plan/add', 'PlanController@save');
    Route::post('/plan/update/{plan}', 'PlanController@update');
    Route::post('/catagory/add', 'CatagoryController@save');
    Route::post('/company/add', 'CompanyController@save');
    Route::post('/company/{company}/visajob', 'CompanyController@addVisa');
    Route::post('/company/update/{company}', 'CompanyController@update');
    Route::post('/applied/notify/{apply}', 'ApplyController@notify');
    Route::post('/cover-letter/add', 'CoverLetterController@save');
    Route::post('/cover-letter/update/{coverLetter}', 'CoverLetterController@update');
    // Route::post('/settings', 'SettingsController@update');
});
