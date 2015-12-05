<?php

namespace Model;

class User extends \Model {

	protected static $requiredFields = ['username', 'fingerprint', 'name', 'passhash', 'private_key', 'public_key', 'symmetric_key'];

	/**
	 * Create and save a new user and related keys
	 * @param  array $data
	 * @return User
	 */
	public static function create(array $data) {
		// Create user
		$user = parent::create($data);

		// Create keys
		$private = User\Key::create(['user_id' => $user->id, 'type' => 'private', 'fingerprint' => $data['fingerprint'], 'key' => $data['private_key']]);
		$public = User\Key::create(['user_id' => $user->id, 'type' => 'public', 'fingerprint' => $data['fingerprint'], 'key' => $data['public_key']]);
		$symmetric = User\Key::create(['user_id' => $user->id, 'buddy_id' => $user->id, 'type' => 'symmetric', 'key' => $data['symmetric_key']]);

		return $user;
	}

	/**
	 * Load a user by token string
	 * @param  string $token
	 * @return User
	 */
	public function loadByToken($token) {
		$tokenModel = new User\Token;
		$tokenModel->load(['token = ?', $token]);
		if($tokenModel->user_id) {
			$this->load($tokenModel->user_id);
		} else {
			throw new Exception('Invalid session token.');
		}
		return $this;
	}

}
