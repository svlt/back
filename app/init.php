<?php
use Pixie\Connection as Db;

// Load configuration
if(is_file('config.php')) {
	$config = require('config.php');
} else {
	throw new Exception('No config.php file found.');
}

// Initialize database connection
$db = new Db('mysql', array('driver' => 'mysql') + $config['db'], 'QB');
