<?php

namespace Models;

class User extends BaseModel {

	protected const INIT_QUERY = <<<SQL
CREATE TABLE IF NOT EXISTS users (
	id INTEGER PRIMARY KEY AUTOINCREMENT,
	email TEXT NOT NULL,
	api_key TEXT NOT NULL
)
SQL;

	public function create (string $email, string $apiKey): void {
		$this->db->exec(
			'INSERT INTO users (email, api_key) VALUES (:email, :api_key)',
			[ 'email' => $email, 'api_key' => $apiKey ]
		);
	}

	public function getApiKey ($email): ?string {
		$result = $this->db->exec(
			'SELECT api_key FROM users WHERE email=?',
			[ $email ]
		);

		if (empty($result)) {
			return null;
		}

		return $result[0]['api_key'];
	}

	public function isApiKeyValid ($apiKey): bool {
		$result = $this->db->exec(
			'SELECT 1 FROM users WHERE api_key=?',
			[ $apiKey ]
		);

		return !empty($result);
	}

}
