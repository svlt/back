<?php

final class App {

	static private $_config;
	static private $_router;
	static private $_qb;
	static private $_db;

	/**
	 * Initialize the app
	 */
	static function init() {

		// Load configuration
		if(is_file('config.php')) {
			self::$_config = require('config.php');
		} else {
			throw new Exception('No config.php file found.');
		}

		// Initialize Composer autoloader
		require_once 'vendor/autoload.php';

		// Initialize framework
		self::$_router = Base::instance();
		self::$_router->mset([
			'AUTOLOAD' => 'app/',
			'ESCAPE' => false,
			'PACKAGE' => 'svlt/back',
		]);

		// Initialize database connection and query builder
		self::$_qb = new Pixie\Connection('mysql', ['driver' => 'mysql'] + self::$_config['db'], 'QB');
		self::$_db = new SQL(QB::pdo(), 'mysql:host='.self::$_config['db']['host'].';port=3306;dbname='.self::$_config['db']['database']);

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
	 * @return Base
	 */
	static function router() {
		return self::$_router;
	}

	/**
	 * Trigger router error
	 * @param int $code
	 */
	static function error($code = null) {
		return self::$_router->error($code);
	}

	/**
	 * Get a database instance
	 * @return DB\SQL
	 */
	static function db() {
		return self::$_db;
	}

	/**
	 * Get a model instance
	 * @param  string $name
	 * @return Model
	 */
	static function model($name, array $args = []) {
		$className = 'Model\\' . str_replace(array('/', '_'), '\\', ucwords($name));
		$class = new ReflectionClass($className);
		return $class->newInstanceArgs($args);
	}

}
