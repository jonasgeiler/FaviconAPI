<?php

namespace Models;

use Base;
use DB\SQL;
use Prefab;

class BaseModel extends Prefab {

	protected const INIT_QUERY = '';

	protected SQL $db;

	/**
	 * Initializes a new database connection, calls parent constructor and loads the user
	 */
	public function __construct () {
		$f3 = Base::instance();
		$this->db = $f3->DB ?? ($f3->DB = new SQL($f3->DB_DSN));

		$this->db->exec(static::INIT_QUERY);
	}

}
