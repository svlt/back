<?php

class SQL extends \DB\SQL {

	/**
	 * Instantiate \DB\SQL using an existing \PDO instance
	 * @param \PDO   $pdo
	 * @param string $dsn
	 */
	function __construct(\PDO $pdo, $dsn) {
		$f3 = \Base::instance();
		$this->pdo = $pdo;
		$this->uuid = $f3->hash($this->dsn = $dsn);
		if (preg_match('/^.+?(?:dbname|database)=(.+?)(?=;|$)/is', $dsn, $parts))
			$this->dbname = $parts[1];
		$this->engine = $this->pdo->getAttribute(\PDO::ATTR_DRIVER_NAME);
	}

}
