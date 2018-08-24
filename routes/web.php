<?php

Auth::routes();

Route::get('/login/google', '\App\Http\Controllers\Auth\LoginController@redirectToProvider');
Route::get('/login/google/callback', '\App\Http\Controllers\Auth\LoginController@handleProviderCallback');

Route::get('/green-card', 'GreenCardController@index')->middleware('cors');
Route::post('/green-card-subscriber', 'GreenCardController@subscribe')->middleware('cors');
Route::get('/绿卡排期', 'GreenCardController@visaBulletin');

Route::get('/', 'PostController@newHomePage');
Route::get('/catagory', 'CatagoryController@allAdmin');
Route::get('/job-list', 'PostController@newHomePageJobs');
Route::get('/searchLocation', 'PostController@searchBarLocation');
Route::get('/searchJob', 'PostController@searchBarJob');
Route::get('/tags', 'TagController@all');
Route::get('/jobs', 'PostController@all');
Route::get('/job/{postSlug}', 'PostController@show');
Route::get('/fetch/post', 'PostController@fetch');
Route::get('/blog', 'BlogController@all');
Route::get('/blog/{blogSlug}', 'BlogController@show');
Route::get('/register/verification', '\App\Http\Controllers\Auth\RegisterController@verify');

Route::post('/apply', 'ApplyController@save');
Route::post('/job/favorite/toggle/{post}', 'FavoriteController@toggle');
Route::post('/applyRegister', '\App\Http\Controllers\Auth\RegisterController@register');

Route::prefix('dashboard')->middleware(['auth'])->group(function () {
    Route::get('/applies', 'UserController@applies');
    Route::get('/account', 'UserController@account');
    Route::get('/favorites', 'UserController@favorites');
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
    Route::get('/applies', 'ApplyController@all')->middleware('master');
    Route::get('/cities', 'LocationController@citiesByState');
    Route::get('/jobs', 'PostController@allAdmin');
    Route::get('/user', 'UserController@all')->middleware('master');
    Route::get('/blog', 'BlogController@allAdmin');
    Route::get('/tags', 'TagController@allAdmin');
    Route::get('/catagory', 'CatagoryController@allAdmin');
    Route::get('/company', 'CompanyController@all');
    Route::get('/plan', 'PlanController@index');
    Route::get('/fetch/applies', 'ApplyController@fetch')->middleware('master');
    Route::get('/cover-letter', 'CoverLetterController@all');
    Route::get('/green-card', 'GreenCardController@alladmin')->middleware('master');
    Route::get('/settings', 'SettingsController@index')->middleware('master');
    Route::get('/visa', 'GreenCardController@crawl')->middleware('master');
    Route::get('/visa/inventory', 'GreenCardController@crawlInventory')->middleware('master');
    Route::get('/companies', 'CompanyController@select2Companies');

    Route::post('/visa', 'GreenCardController@save');
    Route::post('/post/add', 'PostController@save');
    Route::post('/post/update/{post}', 'PostController@update');
    Route::post('/blog/add', 'BlogController@save');
    Route::post('/tag/add', 'TagController@save');
    Route::post('/job/recommend/{post}', 'PostController@recommend');
    Route::post('/blog/update/{blog}', 'BlogController@update');
    Route::post('/tag/update/{tag}', 'TagController@update');
    Route::post('/user/add', 'UserController@save');
    Route::post('/user/suspend/{user}', 'UserController@toggleSuspend');
    Route::post('/user/update/{user}', 'UserController@update');
    Route::post('/plan/add', 'PlanController@save');
    Route::post('/plan/update/{plan}', 'PlanController@update');
    Route::post('/catagory/add', 'CatagoryController@save');
    Route::post('/company/add', 'CompanyController@save');
    Route::post('/company/{company}/visajob', 'CompanyController@addVisa');
    Route::post('/company/update/{company}', 'CompanyController@update');
    Route::post('/applied/notify/{apply}', 'ApplyController@notify');
    Route::post('/send/applies', 'ApplyController@send')->middleware('master');
    Route::post('/cover-letter/add', 'CoverLetterController@save');
    Route::post('/cover-letter/update/{coverLetter}', 'CoverLetterController@update');
    Route::post('/settings', 'SettingsController@update');
    Route::post('/notifySubscribers1', 'GreenCardController@notifySubscribers1');
    Route::post('/notifySubscribers2', 'GreenCardController@notifySubscribers2');

    Route::delete('/job/delete/{post}', 'PostController@delete');
    Route::delete('/tag/delete/{tag}', 'TagController@delete');
    Route::delete('/company/delete/{company}', 'CompanyController@delete');
    Route::delete('/category/delete/{category}', 'CatagoryController@delete');
});
