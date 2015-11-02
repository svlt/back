<?php

namespace Model\User;

class Token extends \Model {

	protected static $requiredFields = ['user_id', 'token'];

	/**
	 * Create and save a new token, automatically setting the
	 * expiration if none is given in $data
	 *
	 * @param  array $data
	 * @return User
	 */
	public static function create(array $data) {
		if(empty($data['expires_at'])) {
			$data['expires_at'] = date('Y-m-d H:i:s', strtotime('+1 week'));
		}
		return parent::create($data);
	}

}
