<?php

namespace Libraries\Favicon;

use Intervention\Image\Image;
use Intervention\Image\ImageManager;
use Libraries\Favicon\File\Ico;
use Libraries\Favicon\File\Png;
use Libraries\Favicon\File\Text;
use ZipArchive;

class Package {

	/**
	 * @var array Generated package with all the images/icons
	 */
	private array $package = [];

	/**
	 * Generator constructor.
	 *
	 * @param \Intervention\Image\Image $image
	 *
	 * @throws \Libraries\Favicon\Exception\NotSquareException
	 * @throws \JsonException
	 */
	public function __construct (Image $image) {
		$this->package['favicon.ico'] = new Ico($image, [ 16, 32, 48 ]);
		$this->package['favicon-16x16.png'] = new Png($image, 16);
		$this->package['favicon-32x32.png'] = new Png($image, 32);
		$this->package['apple-touch-icon.png'] = new Png($image, 180);
		$this->package['android-chrome-192x192.png'] = new Png($image, 192);
		$this->package['android-chrome-512x512.png'] = new Png($image, 512);

		$this->package['manifest.json'] = new Text(
			<<<JSON
{
    "name": "",
    "short_name": "",
    "description": "",
    "theme_color": "#ffffff",
    "background_color": "#ffffff",
    "display": "standalone",
    "start_url": "/",
    "icons": [
        {
            "src": "/android-chrome-192x192.png",
            "sizes": "192x192",
            "type": "image/png"
        },
        {
            "src": "/android-chrome-512x512.png",
            "sizes": "512x512",
            "type": "image/png"
        }
    ]
}
JSON
		);

		$this->package['code.html'] = new Text(
			<<<HTML
<link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png" />
<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png" />
<link rel="icon" type="image/png" sizes="16x16" href="/favicon-16x16.png" />
<link rel="manifest" href="/manifest.json" />
HTML
		);
	}

	/**
	 * @param string $file Image file
	 *
	 * @return \Libraries\Favicon\Package
	 * @throws \JsonException
	 * @throws \Libraries\Favicon\Exception\NotSquareException
	 */
	public static function fromFile (string $file): Package {
		$manager = new ImageManager();

		return new self($manager->make($file));
	}

	/**
	 * @param string $archiveFile Output file for ZIP archive
	 *
	 * @return bool
	 */
	public function writeZip (string $archiveFile): bool {
		$archive = new ZipArchive();

		if ($archive->open($archiveFile, ZipArchive::CREATE) === true) {
			foreach ($this->package as $filename => $contents) {
				$success = $archive->addFromString($filename, $contents->getData());

				if (!$success) {
					return false;
				}
			}

			return $archive->close();
		}

		return false;
	}

}
