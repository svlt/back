<?php

abstract class Controller {

	/**
	 * Require user to authenticate with a token
	 * @return int|bool FALSE or a User ID
	 */
	protected function _requireAuth() {
		$token = isset($_REQUEST['token']) ? $_REQUEST['token'] : (isset($_SERVER['HTTP_TOKEN']) ? $_SERVER['HTTP_TOKEN'] : null);
		return $token ? \Helper\Security::validateToken($token) : false;
	}

	/**
	 * Output JSON response and required headers
	 * @param mixed $response
	 */
	public function _json($response) {
		if(!headers_sent()) {
			header("Content-type: application/json");
		}
		echo json_encode($response);
	}

}
