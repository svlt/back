<?php

namespace Model;

abstract class Readonly extends \Model {
	
	/**
	 * Disable create function
	 * @param  array  $data
	 * @return void
	 * @throws Exception
	 */
	public static function create(array $data) {
		throw new Exception('This class is read-only.');
	}

	/**
	 * Disable property assignment
	 * @param  string $key
	 * @param  mixed $val
	 * @return void
	 * @throws Exception
	 */
	public function set($key, $val) {
		throw new Exception('This class is read-only.');
	}

	/**
	 * Disable property unassignment
	 * @param  string $key
	 * @return void
	 * @throws Exception
	 */
	public function clear($key) {
		throw new Exception('This class is read-only.');
	}

	/**
	 * Disable property assignment
	 * @param  string $key
	 * @param  mixed $val
	 * @return void
	 * @throws Exception
	 */
	public function offsetset($key, $val) {
		throw new Exception('This class is read-only.');
	}

	/**
	 * Disable property unassignment
	 * @param  string $key
	 * @return void
	 * @throws Exception
	 */
	public function offsetunset($key) {
		throw new Exception('This class is read-only.');
	}

}
