<?php

namespace Libraries\Favicon\File;

use Intervention\Image\Image;
use Libraries\Favicon\Exception\NoImageException;
use Libraries\Favicon\Exception\NotSquareException;


/**
 * This class generates ICO files
 *
 * @package Libraries\FaviconGenerator
 */
class Ico implements File {

	/**
	 * @var array Images in the BMP format
	 */
	private array $images = [];

	/**
	 * Constructor for the ICO generator.
	 *
	 * If not source image was passed, the image(s) will need to be supplied using the
	 * {@link \Libraries\Favicon\Ico::addImage()} method.
	 *
	 * @param \Intervention\Image\Image|null $sourceImage
	 * @param array                          $sizes An array of sizes (each size is an array with a width and height) that the source image should be rendered at in the generated ICO file.
	 *                                              If sizes are not supplied, the size of the source image will be used.
	 *
	 * @throws \Libraries\Favicon\Exception\NotSquareException
	 */
	public function __construct (Image $sourceImage = null, array $sizes = []) {
		if ($sourceImage !== null) {
			$this->addImage($sourceImage, $sizes);
		}
	}

	/**
	 * Add an image to the generator
	 *
	 * @param \Intervention\Image\Image $image
	 * @param array                     $sizes An array of sizes (each size is an array with a width and height) that the image should be rendered at in the generated ICO file.
	 *                                         If sizes are not supplied, the size of the source image will be used.
	 *
	 * @throws \Libraries\Favicon\Exception\NotSquareException
	 */
	public function addImage (Image $image, array $sizes = []): void {
		if ($image->getWidth() !== $image->getHeight()) {
			throw new NotSquareException('Image must be a square');
		}

		if (empty($sizes)) {
			$sizes = [ $image->getWidth() ];
		}

		rsort($sizes); // Sort sizes highest to lowest

		foreach ($sizes as $targetSize) {
			$currentSize = $image->getWidth();

			while ($currentSize / 2 >= $targetSize) {
				$currentSize /= 2;
				$image->resize($currentSize, $currentSize);
			}

			if ($currentSize > $targetSize) {
				$clone = clone $image;
				$clone->resize($targetSize, $targetSize);
				$this->addImageData($clone, $targetSize);
			} else {
				$this->addImageData($image, $targetSize);
			}
		}
	}

	/**
	 * Take an image resource and change it into a raw BMP format.
	 *
	 * @param \Intervention\Image\Image $image
	 * @param                           $size
	 */
	private function addImageData (Image $image, $size): void {
		$pixelData = [];

		$opacityData = [];
		$currOpacity = 0;

		for ($y = $size - 1; $y >= 0; $y--) {
			for ($x = 0; $x < $size; $x++) {
				$color = $image->pickColor($x, $y, 'int');

				$alpha = ($color & 0x7F000000) >> 24;
				$alpha = (1 - ($alpha / 127)) * 255;

				$color &= 0xFFFFFF;
				$color |= 0xFF000000 & ($alpha << 24);

				$pixelData[] = $color;


				$opacity = ($alpha <= 127) ? 1 : 0;

				$currOpacity = ($currOpacity << 1) | $opacity;

				if ((($x + 1) % 32) === 0) {
					$opacityData[] = $currOpacity;
					$currOpacity = 0;
				}
			}

			if (($x % 32) > 0) {
				while (($x++ % 32) > 0) {
					$currOpacity <<= 1;
				}

				$opacityData[] = $currOpacity;
				$currOpacity = 0;
			}
		}

		$imageHeaderSize = 40;
		$colorMaskSize = $size * $size * 4;
		$opacityMaskSize = (ceil($size / 32) * 4) * $size;

		$data = pack('VVVvvVVVVVV', 40, $size, ($size * 2), 1, 32, 0, 0, 0, 0, 0, 0);

		foreach ($pixelData as $color) {
			$data .= pack('V', $color);
		}

		foreach ($opacityData as $opacity) {
			$data .= pack('N', $opacity);
		}

		$this->images[] = [
			'size'               => $size,
			'colorPaletteColors' => 0,
			'bitsPerPixel'       => 32,
			'dataSize'           => $imageHeaderSize + $colorMaskSize + $opacityMaskSize,
			'data'               => $data,
		];
	}

	/**
	 * Generate the final ICO data by creating a file header and adding the image data.
	 *
	 * @return string
	 * @throws \Libraries\Favicon\Exception\NoImageException
	 */
	public function getData (): string {
		if (!is_array($this->images) || empty($this->images)) {
			throw new NoImageException('No image given');
		}

		$data = pack('vvv', 0, 1, count($this->images));
		$pixelData = '';

		$iconDirEntrySize = 16;

		$offset = 6 + ($iconDirEntrySize * count($this->images));

		foreach ($this->images as $image) {
			$data .= pack(
				'CCCCvvVV',
				$image['size'],
				$image['size'],
				$image['colorPaletteColors'],
				0,
				1,
				$image['bitsPerPixel'],
				$image['dataSize'],
				$offset
			);

			$pixelData .= $image['data'];
			$offset += $image['dataSize'];
		}

		$data .= $pixelData;
		unset($pixelData);

		return $data;
	}

}
