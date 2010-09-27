<?php defined('SYSPATH') or die('No direct script access.');

//-- Environment setup --------------------------------------------------------

/**
 * Set the default time zone.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/timezones
 */
date_default_timezone_set('America/Chicago');

/**
 * Set the default locale.
 *
 * @see  http://docs.kohanaphp.com/about.configuration
 * @see  http://php.net/setlocale
 */
setlocale(LC_ALL, 'en_US.utf-8');

/**
 * Enable the Kohana auto-loader.
 *
 * @see  http://docs.kohanaphp.com/about.autoloading
 * @see  http://php.net/spl_autoload_register
 */
spl_autoload_register(array('Kohana', 'auto_load'));

/**
 * Enable the Kohana auto-loader for unserialization.
 *
 * @see  http://php.net/spl_autoload_call
 * @see  http://php.net/manual/var.configuration.php#unserialize-callback-func
 */
ini_set('unserialize_callback_func', 'spl_autoload_call');

//-- Configuration and initialization -----------------------------------------

/**
 * Initialize Kohana, setting the default options.
 *
 * The following options are available:
 *
 * - string   base_url    path, and optionally domain, of your application   NULL
 * - string   index_file  name of your index file, usually "index.php"       index.php
 * - string   charset     internal character set used for input and output   utf-8
 * - string   cache_dir   set the internal cache directory                   APPPATH/cache
 * - boolean  errors      enable or disable error handling                   TRUE
 * - boolean  profile     enable or disable internal profiling               TRUE
 * - boolean  caching     enable or disable internal caching                 FALSE
 */
Kohana::init(
	array(
		'base_url' => '/',
		'index_file' => false,
		'profile' => ! IN_PRODUCTION,
		'caching' => IN_PRODUCTION
	)
);

/**
 * Attach the file write to logging. Multiple writers are supported.
 */
Kohana::$log->attach(new Kohana_Log_File(APPPATH.'logs'));

/**
 * Attach a file reader to config. Multiple readers are supported.
 */
Kohana::$config->attach(new Kohana_Config_File);

/**
 * Enable modules. Modules are referenced by a relative or absolute path.
 */
Kohana::modules(
	array(
		'auth'       => MODPATH . 'auth',
		'database'   => MODPATH . 'database',
		'orm'        => MODPATH . 'orm',
        'flash'      => MODPATH . 'flash',
		'pagination' => MODPATH . 'pagination',
	)
);

// Push admin controllers to the admin directory
Route::set( 'admin', 'admin(/<controller>(/<action>(/<id>)))' )
	->defaults(
		array(
			'directory'  => 'admin',
			'controller' => 'home',
			'action'     => 'index',
		)
	);

# Shortcuts for content pages
Route::set( 'about', 'about')->defaults( array( 'controller' => 'content', 'action' => 'index', 'id' => 'about' ) );
Route::set( 'home', 'home')->defaults( array( 'controller' => 'content', 'action' => 'index', 'id' => 'home' ) );

# Long form of content pages
Route::set( 'content', '(content(/<id>))')
	->defaults(
		array(
			'controller' => 'content',
			'action'     => 'index',
			'id' => 'home'
		)
	);

# Shortcuts for user actions
Route::set( 'login', 'login' )->defaults( array( 'controller' => 'user', 'action' => 'login' ) );
Route::set( 'logout', 'logout' )->defaults( array( 'controller' => 'user', 'action' => 'logout' ) );
Route::set( 'signup', 'signup' )->defaults( array( 'controller' => 'user', 'action' => 'signup' ) );


# And the four option default
Route::set( 'default', '(<controller>(/<action>(/<id>(/<argument>))))' )
	->defaults(
		array(
			'controller' => 'content',
			'action'     => 'index',
			'id' => 'home',
			'argument' => null
		)
	);



/**
 * Execute the main request. A source of the URI can be passed, eg: $_SERVER['PATH_INFO'].
 * If no source is specified, the URI will be automatically detected.
 */
$request = Request::instance();
$request->execute();
$request->send_headers();
Flash::update();
echo $request->response;