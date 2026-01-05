<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
public function boot()
{
    // Traduzir paginação para português
    Paginator::currentPathResolver(function () {
        return \Request::url();
    });
    
    Paginator::currentPageResolver(function ($pageName = 'page') {
        $page = \Request::input($pageName);
        return filter_var($page, FILTER_VALIDATE_INT) !== false ? (int) $page : 1;
    });
}
}
