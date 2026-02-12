<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;

final class UserService
{
	public function __construct(
		private Explorer $db,
	) {}

	public function register(
		string $email,
		string $password,
		string $username,
		string $firstName,
		string $lastName,
		string $role = 'student',
	): void
	{
		$this->db->table('users')->insert([
			'email' => trim($email),
			'username' => trim($username),
			'first_name' => trim($firstName),
			'last_name' => trim($lastName),
			'password_hash' => password_hash($password, PASSWORD_DEFAULT),
			'role' => $role,
			'created_at' => new \DateTimeImmutable(),
		]);
	}
}
