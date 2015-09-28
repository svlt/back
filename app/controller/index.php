<?php

namespace Controller;

class Index extends \Controller {

	/**
	 * GET /
	 */
	public function root() {
		$this->_json([
			'message' => 'This is the Social Vault API. Documentation is available at https://svlt.github.io/.',
		]);
	}

	/**
	 * GET /ping.json
	 */
	public function ping() {
		$this->_json('Pong!');
	}

}
