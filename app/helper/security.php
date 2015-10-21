<?php

namespace Helper;

class Security {

	/**
	 * Generate a session token by user ID
	 * @param  int $user_id
	 * @param  int $expires
	 * @return string
	 */
	static function generateToken($user_id, $expires = null) {
		$hash = self::randChars(128);
		$token = \App::model('user/token');
		$token->set('user_id', $user_id);
		$token->set('expires_at', date('Y-m-d H:i:s', $expires ?: strtotime('+1 week')));
		$token->set('token', $hash);
		$token->save();
		return $hash;
	}

	/**
	 * Validate a session token, returning the user ID
	 * @param  string $token
	 * @return int|bool FALSE or a User ID
	 */
	static function validateToken($tokenString) {
		$token = \App::model('user/token');
		$token->load($tokenString, 'token');
		if($token->get('id')) {
			if(strtotime($token->get('expires_at')) > time()) {
				return $token->get('user_id');
			} else {
				$token->delete();
			}
		}
		return false;
	}

	/**
	 * Generate a hexadecimal SHA512 Hash
	 * @param  string $data
	 * @return string (128 hexits)
	 */
	static function sha512($data) {
		return hash('sha512', $data);
	}

	/**
	 * Generate secure random bytes
	 * @param  int $length
	 * @return string
	 */
	static function randBytes($length = 256) {
		if(function_exists('random_bytes')) { // PHP 7
			return random_bytes($length);
		}
		if(function_exists('openssl_random_pseudo_bytes')) { // OpenSSL
			$result = openssl_random_pseudo_bytes($length, $strong);
			if(!$strong) {
				throw new Exception('OpenSSL failed to generate secure randomness.');
			}
			return $result;
		}
		if(file_exists('/dev/urandom') && is_readable('/dev/urandom')) { // Unix
			$fh = fopen('/dev/urandom', 'rb');
			if ($fh !== false) {
				$result = fread($fh, $length);
				fclose($fh);
				return $result;
			}
		}
		throw new Exception('No secure random source available.');
	}

	/**
	 * Generate secure random printable characters
	 * @param  int $length
	 * @return string
	 */
	static function randChars($length = 64) {
		$chars = '';
		while(strlen($chars) < $length) {
			$chars .= preg_replace('/[^a-zA-Z0-9~!@#$%^&*_-]/i', '', self::randBytes($length * 4));
		}
		return substr($chars, 0, $length);
	}

}
