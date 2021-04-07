<?php

namespace Controllers;

use View;

class Privacy {

	public function index () {
		echo View::instance()->render('privacy.php');
	}

}
