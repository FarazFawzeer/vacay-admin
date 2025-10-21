<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

register_shutdown_function(function () {
    $duration = microtime(true) - LARAVEL_START;
    file_put_contents(storage_path('logs/load_time.log'), "Request took {$duration} seconds\n", FILE_APPEND);
});

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__.'/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
(require_once __DIR__.'/../bootstrap/app.php')
    ->handleRequest(Request::capture());
