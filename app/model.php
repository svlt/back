<?php

abstract class Model extends \DB\SQL\Mapper {

	protected static $requiredFields = [];

	/**
	 * Initialize model instance
	 */
	function __construct() {
		parent::__construct(App::db(), $this->getTableName());
	}

	/**
	 * Get the database table name for the model
	 * @return string
	 */
	public function getTableName() {
		return strtolower(str_replace('\\', '_', trim(preg_replace('/^model/i', '', get_class($this)), '\\')));
	}

	/**
	 * Create and save a new item
	 * @param  array $data
	 * @return Model
	 */
	public static function create(array $data) {
		$item = new static();

		// Check required fields
		foreach(self::$requiredFields as $field) {
			if(!isset($data[$field])) {
				throw new Exception("Required field $field not specified.");
			}
		}

		// Set field values
		foreach($data as $key => $val) {
			if($item->exists($key)) {
				if(empty($val)) {
					$val = null;
				}
				$item->set($key, $val);
			}
		}

		// Set auto values if they exist
		if($item->exists("created_at") && !isset($data["created_at"])) {
			$item->set("created_at", date("Y-m-d H:i:s"));
		}

		$item->save();
		return $item;
	}

	/**
	 * Load by ID directly if a number is passed
	 * @param  string|array  $filter
	 * @param  array         $options
	 * @param  integer       $ttl
	 * @return mixed
	 */
	function load($filter=NULL, array $options=NULL, $ttl=0) {
		if(is_numeric($filter)) {
			return parent::load(array("id = ?", $filter), $options, $ttl);
		} else {
			return parent::load($filter, $options, $ttl);
		}
	}

	/**
	 * Get or set the model data
	 * @param  array|null $data
	 * @return Model|array
	 */
	public function data(array $data = null) {
		if($data === null) {
			$return = [];
			$fields = $this->fields();
			foreach($fields as $f) {
				$return[$f] = $this->get($f);
			}
			return $return;
		} else {
			foreach($data as $key=>$val) {
				$this->set($key, $val);
			}
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
