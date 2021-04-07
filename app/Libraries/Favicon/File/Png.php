<?php

namespace Libraries\Favicon\File;

use Intervention\Image\Image;
use Libraries\Favicon\Exception\NotSquareException;

class Png implements File {

	/**
	 * @var \Intervention\Image\Image
	 */
	private Image $image;

	/**
	 * Png constructor.
	 *
	 * @param \Intervention\Image\Image $image
	 * @param int                       $targetSize Target size of the image
	 *
	 * @throws \Libraries\Favicon\Exception\NotSquareException
	 */
	public function __construct (Image $image, int $targetSize) {
		if ($image->getWidth() !== $image->getHeight()) {
			throw new NotSquareException('Image must be a square');
		}

		$clone = clone $image;
		$currentSize = $clone->getWidth();

		while ($currentSize / 2 >= $targetSize) {
			$currentSize /= 2;
			$clone->resize($currentSize, $currentSize);
		}

		if ($currentSize > $targetSize) {
			$clone->resize($targetSize, $targetSize);
		}

		$this->image = $clone;
	}

	/**
	 * Returns the image data encoded as PNG
	 *
	 * @return string
	 */
	public function getData (): string {
		return (string) $this->image->encode('png');
	}

}
