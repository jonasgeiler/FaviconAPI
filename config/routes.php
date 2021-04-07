<?php

/*
|--------------------------------------------------------------------------
| Get application instance
|--------------------------------------------------------------------------
|
| Here we will get the application instance that serves as
| the central piece of this framework.
|
*/

$f3 = \Base::instance();



/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Fat-Free the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$f3->route('GET /','Controllers\Home->index', 86400); // Home, expires in a day
$f3->route('GET /privacy','Controllers\Privacy->index', 86400); // Privacy Policy, expires in a day

// API
$f3->route('POST /api/register','Controllers\Api->register'); // Register email to get API key
$f3->route('POST /api/generate','Controllers\Api->generate'); // Generate favicon package
