<?php

abstract class Model {

	public function getTableName() {
		return strtolower(preg_replace('/$model\\/i', '', get_class($this)));
	}

	public function load($col = null, $val = null) {
		// TODO: load data without requiring another instance
		return QB::table($this->getTableName())->asObject(get_class($this))->find($col, $val);
	}

	public function save() {
		// TODO: implement save functionality
	}

}

