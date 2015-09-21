<?php

abstract class Controller {

	/**
	 * Require user to authenticate with a token
	 * @return int|bool FALSE or a User ID
	 */
	protected function _requireAuth() {
		return isset($_REQUEST['token']) ? \Helper\Security::validateToken($_REQUEST['token']) : false;
	}

}
