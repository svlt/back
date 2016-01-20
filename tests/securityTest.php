<?php

class SecurityTest extends PHPUnit_Framework_TestCase {

	public function testSha512() {
		$this->assertEquals(
			'ee26b0dd4af7e749aa1a8ee3c10ae9923f618980772e473f8819a5d4940e0db27ac185f8a0e1d5f84f88bc887fd67b143732c304cc5fa9ad8e6f57f50028a8ff',
			\Helper\Security::sha512('test')
		);
	}

	public function testRandBytes() {
		$this->assertEquals(
			64,
			strlen(\Helper\Security::randBytes(64))
		);
	}

	public function testRandCharsSafe() {
		$this->assertRegExp(
			'/^[a-z0-9_-]{64}$/i',
			\Helper\Security::randChars(64, true)
		);
	}

	public function testRandChars() {
		$this->assertRegExp(
			'/^[a-z0-9~!@#$%^&*_-]{64}$/i',
			\Helper\Security::randChars(64)
		);
	}

}
