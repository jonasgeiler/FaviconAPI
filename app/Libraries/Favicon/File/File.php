<?php

namespace Libraries\Favicon\File;

interface File {

	/**
	 * Return data of the file
	 *
	 * @return string
	 */
	public function getData (): string;

}
