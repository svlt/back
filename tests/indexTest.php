<?php

class IndexTest extends PHPUnit_Framework_TestCase {

	public function testRoot() {
		$this->expectOutputRegex('/API/');
		\Base::instance()->mock('GET /');
	}

	public function testPing() {
		$this->expectOutputString('"Pong!"');
		\Base::instance()->mock('GET /ping.json');
	}

}
