<?php

namespace Libraries\Favicon\File;

class Text implements File {

	/**
	 * @var string
	 */
	private string $content;

	/**
	 * File constructor.
	 *
	 * @param string $content
	 */
	public function __construct (string $content) {
		$this->content = $content;
	}

	/**
	 * Return text
	 *
	 * @return string
	 */
	public function getData (): string {
		return $this->content;
	}

}
