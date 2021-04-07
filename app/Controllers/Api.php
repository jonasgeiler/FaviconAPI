<?php

namespace Controllers;

use Base;
use Helpers\Key;
use Libraries\Favicon\Exception\NotSquareException;
use Libraries\Favicon\Package;
use Models\User;
use SMTP;
use Web;

class Api {

	public function __construct (Base $f3) {
		$f3->AJAX = true;
		header('Content-Type: application/json');
	}

	/**
	 * Creates a new user and sends their API key to the specified email address
	 *
	 * @param \Base $f3
	 *
	 * @throws \JsonException
	 * @throws \Exception
	 */
	public function register (Base $f3): void {
		$email = $_POST['email'] ?? null;

		if (!$email) {
			$f3->error(400, 'Email is required');
		}

		$user = User::instance();
		$apiKey = $user->getApiKey($email);

		if (!$apiKey) {
			$apiKey = Key::generate();
			$user->create($email, $apiKey);
		}

		$smtp = new SMTP($f3->SMTP_HOST, $f3->SMTP_PORT, $f3->SMTP_SCHEME, $f3->SMTP_USERNAME, $f3->SMTP_PASSWORD);

		$smtp->set('From', $f3->EMAIL_FROM);
		$smtp->set('To', $email);
		$smtp->set('Subject', 'Your Favicon API Key');

		$message = <<<EMAIL
Hi,

Here is your API key: $apiKey

Have fun!
EMAIL;

		$success = $smtp->send($message);

		if (!$success) {
			$f3->error(500, 'Could not send email');
		}

		echo "{}"; // Just empty JSON
	}

	/**
	 * Generates a favicon package from the uploaded image
	 *
	 * @param \Base $f3
	 *
	 * @throws \Exception
	 */
	public function generate (Base $f3): void {
		Key::validate();

		$web = Web::instance();
		$hash = $f3->hash(mt_rand());

		$files = $web->receive(
			fn($file) => in_array($file['type'], [
				'image/png',
				'image/jpeg',
				'image/gif',
				'image/webp',
			], true),
			true,
			fn($filename) => "$hash." . pathinfo($filename, PATHINFO_EXTENSION)
		);

		$filesCount = count($files);

		if ($filesCount === 0) {
			$f3->error(400, 'No images uploaded');
		} elseif ($filesCount > 1) {
			$f3->error(400, 'You can only upload one image at a time');
		}

		$uploadedImage = array_keys($files)[0];

		if (!$files[$uploadedImage]) {
			$f3->error(416, 'File is not an image');
		}

		try {
			$package = Package::fromFile($uploadedImage);
		} catch (NotSquareException $e) {
			$f3->error(400, 'Image is not a square');
			return; // So PHPStorm is happy...
		}

		unlink($uploadedImage);

		$zipFile = "/download/$hash.zip";
		$success = $package->writeZip($f3->PUBLIC . $zipFile);

		if (!$success) {
			$f3->error(500, 'Could not create ZIP archive');
		}

		echo json_encode([
			'download_url' => $f3->URL . $zipFile,
		], JSON_THROW_ON_ERROR);
	}

}
