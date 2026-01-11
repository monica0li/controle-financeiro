<?php

// Se não tem .env, cria um temporário
if (!file_exists(dirname(__DIR__) . '/.env') && getenv('APP_KEY')) {
    $envContent = '';
    $envVars = [
        'APP_KEY', 'APP_DEBUG', 'APP_URL', 'APP_ENV',
        'DB_HOST', 'DB_DATABASE', 'DB_USERNAME', 'DB_PASSWORD',
        'DB_CONNECTION', 'DB_PORT'
    ];
    
    foreach ($envVars as $var) {
        if ($value = getenv($var)) {
            $envContent .= "$var=$value\n";
        }
    }
    
    file_put_contents(dirname(__DIR__) . '/.env', $envContent);
}

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
