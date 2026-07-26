<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Determine if the application is under maintenance...
if (file_exists($maintenance = __DIR__.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Auto Loader...
require __DIR__.'/vendor/autoload.php';

// Bootstrap Laravel...
$app = require_once __DIR__.'/bootstrap/app.php';

// Bind public path to project root for subfolder / testing server deployment
$app->usePublicPath(__DIR__);

// Handle request...
$app->handleRequest(Request::capture());
