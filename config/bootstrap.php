<?php

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader
| for our application. We just need to utilize it! We'll require it
| into the script here so that we do not have to worry about the
| loading of any our classes "manually". Feels great to relax.
|
*/

require_once ROOT . '/lib/autoload.php';



/*
|--------------------------------------------------------------------------
| Bring in (env)
|--------------------------------------------------------------------------
|
| Quickly use our environment variables
|
*/

\Dotenv\Dotenv::createImmutable(ROOT)->load();



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
| Load Config File
|--------------------------------------------------------------------------
|
| Now we will load the the "globals" configuration file, which
| contains various framework configuration for Fat-Free.
|
*/

require __DIR__ . '/globals.php';



/*
|--------------------------------------------------------------------------
| Load The Application Routes
|--------------------------------------------------------------------------
|
| Next we will include the routes config file so that they can all
| be added to the application. This will provide all of the URLs
| the application can respond to, as well as the controllers
| that may handle them.
|
*/

require __DIR__ . '/routes.php';



return $f3;
