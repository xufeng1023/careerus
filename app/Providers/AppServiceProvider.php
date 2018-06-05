<?php

namespace App\Providers;

use App\{Catagory, Company, State, City};
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\{Schema,Blade};

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        \Carbon\Carbon::setLocale(app()->getLocale());

        Blade::if('role', function ($role) {
            return auth()->user()->role === $role;
        });

        \View::composer(['admin.posts'], function($view) {
            $view->with('catagories', Catagory::all());
            $view->with('companies', Company::all());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
