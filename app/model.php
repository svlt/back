<?php

abstract class Model implements ArrayAccess {

	protected $_data = [];

	/**
	 * Get the database table name for the model
	 * @return string
	 */
	public function getTableName() {
		return strtolower(trim(preg_replace('/^model/i', '', get_class($this)), '\\'));
	}

	/**
	 * Find all rows, optionally matching a filter
	 * @param  mixed  $value
	 * @param  string $field
	 * @return Model
	 */
	public function load($value, $field = null) {
		$data = QB::table($this->getTableName())->find($value, $field);

		// TODO: set properties automagically

		return $this;
	}

	/**
	 * Find all rows, optionally matching a filter
	 * @param  string $field
	 * @param  mixed  $value
	 * @return stdClass
	 */
	public function find($field = null, $value = null) {
		return QB::table($this->getTableName())->findAll($field, $value);
	}

	/**
	 * Saves any changes made to the database
	 * @return Model
	 */
	public function save() {
		// TODO: implement save functionality
	}

}
