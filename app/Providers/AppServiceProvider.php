<?php

namespace App\Providers;

use Illuminate\Pagination\Paginator;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\App;
use Carbon\Carbon;

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
    public function boot(): void
    {
        // Definir locale para português do Brasil
        App::setLocale('pt_BR');
        
        // Configurar Carbon para português
        Carbon::setLocale('pt_BR');
        
        // Usar o estilo do Tailwind para paginação
        Paginator::useTailwind();
    }
}