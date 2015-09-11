<?php

abstract class Model {

	protected
		$_id,
		$_origData = [],
		$_data = [];

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
		if($data) {
			$this->_id = $data->id;
			$this->_origData = (array)$data;
			$this->_data = $this->_origData;
		} else {
			$this->_id = null;
			$this->_origData = null;
			$this->_data = null;
		}
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
		if($this->_id) {
			$diff = array_diff_assoc($this->_data, $this->origData);
			QB::table($this->getTableName())->where('id', '=', $this->_id)->update($diff);
		} else {
			QB::table($this->getTableName())->insert($this->_data);
		}
		$this->_origData = $this->_data;
		return $this;
	}

	/**
	 * Get a value from the model
	 * @param  string $key
	 * @return mixed
	 */
	public function get($key) {
		return isset($this->_data[$key]) ? $this->_data[$key] : null;
	}

	/**
	 * Set a value on the model
	 * @param  string $key
	 * @param  mixed  $value
	 * @return Model
	 */
	public function set($key, $value) {
		$this->_data[$key] = $value;
		return $this;
	}

	/**
	 * Check if a value is set on the model
	 * @param  string $key
	 * @return boolean
	 */
	public function has($key) {
		return isset($this->_data[$key]) ? $this->_data[$key] !== null : false;
	}

	/**
	 * Get or set the model data
	 * @param  array|null $data
	 * @return Model|array
	 */
	public function data(array $data = null) {
		if($data === null) {
			return $this->_data;
		} else {
			$this->_data = $data;
			return $this;
		}
	}

	/**
	 * Get multiple requested fields
	 * @param  array $fields
	 * @return array
	 */
	public function getFields(array $fields) {
		$return = [];
		foreach($fields as $field) {
			$return[$field] = $this->get($field);
		}
		return $return;
	}

}
