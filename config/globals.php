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
| Framework Variables
|--------------------------------------------------------------------------
|
| Here we will set the framework variables (globals) which
| defines the behaviour of the framework and
| will be accessible from all included files.
|
*/


/*
 * Absolute path to document root folder.
 */
$f3->ROOT = ROOT;


/**
 * True if the app runs in a production environment
 * THIS IS A CUSTOM VARIABLE - NOT USED BY THE FRAMEWORK
 */
$f3->PRODUCTION = isset($_ENV['APP_ENV']) && $_ENV['APP_ENV'] === 'production';


/**
 * Timezone to use.
 * Changing this value automatically calls the underlying PHP function date_default_timezone_set().
 * See https://php.net/manual/en/timezones.php
 */
$f3->TZ = $_ENV['APP_TIMEZONE'] ?? 'UTC';


/**
 * Seed used for CSRF token generation and as prefix name for cache entries and temp filenames.
 * Uses a hash generated from the server name by default.
 */
if (!empty($_ENV['APP_SEED'])) {
	$f3->SEED = $f3->hash($_ENV['APP_SEED']);
}


/*
 * Verbosity level of the stack trace. Assign values between 0 to 3 for increasing verbosity levels as follow:
 *
 * 0: suppresses logs of the stack trace.
 * 1: logs files & lines.
 * 2: logs classes & functions as well.
 * 3: logs detailed infos of the objects as well.
 */
$f3->DEBUG = $f3->PRODUCTION ? 0 : 3;


/*
 * Search path(**s**) for user-defined PHP classes that the framework will attempt to autoload at runtime.
 * When specifying multiple paths, you can use a pipe (|), comma (,), or semi-colon (;) as path separator.
 * See https://fatfreeframework.com/3.7/routing-engine#the-f3-autoloader
 */
$f3->AUTOLOAD = ROOT . '/app/';


/*
 * Search path for user interface files used by the View and Template classes' render() method.
 * Accepts a pipe (|), comma (,), or semi-colon (;) as separator for multiple paths.
 */
$f3->UI = ROOT . '/resources/views/';


/*
 * Location of the language(s) dictionaries.
 * To enable caching for dictionaries from a config file, add a second parameter with the TTL.
 */
$f3->LOCALES = ROOT . '/resources/lang/';


/*
 * Temporary folder for filesystem locks, compiled F3 templates, etc.
 */
$f3->TEMP = ROOT . '/storage/tmp/';


/*
 * Location of custom logs.
 */
$f3->LOGS = ROOT . '/storage/logs/';


/*
 * Directory where file uploads are saved.
 */
$f3->UPLOADS = ROOT . '/storage/uploads/';


/*
 * Cache backend.
 * F3 can handle Memcache module, APC, WinCache, XCache and a filesystem-based cache.
 *
 * For example: if you'd like to use the memcache module, a configuration string is required,
 * e.g. $f3->set('CACHE','memcache=localhost') (port 11211 by default) or $f3->set('CACHE','memcache=192.168.72.72:11212').
 *
 * When set to TRUE, or when the connection with the specified memcached server above failed, F3 will auto-detect,
 * in that order, the presence of APC, WinCache, XCache and use the first available of these PHP module.
 * If none of these shared memory engine has been detected or is available, a filesystem-based backend is used as a fallback
 * (you can also explicitly specify a folder, e.g. $f3->set('CACHE','folder=/var/tmp/f3filescache/').
 *
 * The framework doesn't use any cache engine when a FALSE value is assigned.
 */
$f3->CACHE = 'folder=' . ROOT . '/storage/cache/';


/*
 * A string containing the X-Powered-By header.
 * If empty, the header is not sent.
 */
$f3->PACKAGE = null;


/**
 * Simple Error Handler.
 * Feel free to customize this however you want!
 *
 * @param \Base $f3
 */
$f3->ONERROR = static function (\Base $f3) {
	$error = $f3->ERROR;

	$logger = new \Log(date('Y-m-d') . '.txt');
	$logger->write($error['text'] . "\n" . $error['trace']);

	unset($error['level']);
	if ($f3->PRODUCTION) {
		unset($error['trace']);
	}

	if ($f3->AJAX) {
		$error['success'] = false;
		echo json_encode($error, JSON_THROW_ON_ERROR);
	} else {
		echo \View::instance()->render('_error.php', 'text/html', $error);
	}
};


/**
 * App URL used in some views
 */
$f3->URL = $_ENV['APP_URL'] ?? 'http://example.com';


/**
 * Location of the public directory.
 */
$f3->PUBLIC = ROOT . '/public';


/**
 * Database connection data
 */
$f3->DB_USERNAME = $_ENV['DB_USERNAME'] ?? 'root';
$f3->DB_PASSWORD = $_ENV['DB_PASSWORD'] ?? '';
$f3->DB_DSN =
	($_ENV['DB_CONNECTION'] ?? 'mysql') .
	':host=' .
	($_ENV['DB_HOST'] ?? 'localhost') .
	';port=' .
	($_ENV['DB_PORT'] ?? 3306) .
	';dbname=' .
	($_ENV['DB_DATABASE'] ?? 'favicon-api');


/**
 * SMTP connection data
 */
$f3->SMTP_HOST = $_ENV['SMTP_HOST'] ?? 'localhost';
$f3->SMTP_PORT = $_ENV['SMTP_PORT'] ?? 25;
$f3->SMTP_SCHEME = $_ENV['SMTP_SCHEME'] ?? null;
$f3->SMTP_USERNAME = $_ENV['SMTP_USERNAME'] ?? null;
$f3->SMTP_PASSWORD = $_ENV['SMTP_PASSWORD'] ?? null;
$f3->EMAIL_FROM = $_ENV['EMAIL_FROM'] ?? 'no-reply@example.com';
