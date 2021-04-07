<?php

namespace Helpers;

use Base;
use Models\User;

class Key {

	/**
	 * @param int $length
	 *
	 * @return false|string
	 * @throws \Exception
	 */
	public static function generate ($length = 40): bool|string {
		$bytes = random_bytes($length);

		return substr(str_replace([ '+', '/' ], '', base64_encode($bytes)), 0, $length);
	}

	/**
	 * Validates the API key provided in the header
	 */
	public static function validate (): void {
		$f3 = Base::instance();
		$user = User::instance();

		$apiKey = $f3->HEADERS['X-Api-Key'] ?? null;

		if (!$apiKey || !$user->isApiKeyValid($apiKey)) {
			$f3->error(401, 'Invalid API key provided');
		}
	}

}
