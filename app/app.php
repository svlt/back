<?php

final class App {

	static private $_config;
	static private $_router;
	static private $_db;

	/**
	 * Initialize the app
	 */
	static function init() {

		// Register autoload function
		spl_autoload_register(array('App', 'autoload'), true, true);

		// Load configuration
		if(is_file('config.php')) {
			$config = require('config.php');
		} else {
			throw new Exception('No config.php file found.');
		}

		// Initialize Composer autoloader
		require_once 'vendor/autoload.php';

		// Initialize database connection
		$db = new Pixie\Connection('mysql', array('driver' => 'mysql') + $config['db'], 'QB');

		// Initialize routes
		require_once 'routes.php';

	}

	/**
	 * Automatically load classes when required
	 * @param string $class
	 */
	static function autoload($class) {
		$filename = strtolower(str_replace('\\', '/', $class)) . '.php';
		if(is_file(__DIR__ . DIRECTORY_SEPARATOR . $filename)) {
			require_once __DIR__ . DIRECTORY_SEPARATOR . $filename;
		}
	}

	/**
	 * Get a router instance
	 * @return Klein\Klein
	 */
	static function router() {
		if(!self::$_router) {
			self::$_router = new Klein\Klein;
		}
		return self::$_router;
	}

	/**
	 * Trigger router error
	 * @param int $code
	 */
	static function error($code = null) {
		return self::router()->abort($code);
	}

	/**
	 * Get a query builder instance
	 * @return Pixie\Connection
	 */
	static function db() {
		return self::$_db;
	}

	/**
	 * Get a model instance
	 * @param  string $name
	 * @return Model
	 */
	static function model($name, $args = array()) {
		$className = 'Model\\' . str_replace('/', '\\', ucwords($name));
		$class = new ReflectionClass($className);
		return $class->newInstanceArgs($args);
	}

}
