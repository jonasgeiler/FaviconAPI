<?php

namespace Controllers;

use View;

class Home {

	public function index () {
		echo View::instance()->render('home.php');
	}

}
