<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Security\Authenticator;
use Nette\Security\AuthenticationException;
use Nette\Security\IIdentity;
use Nette\Security\SimpleIdentity;

final class DbAuthenticator implements Authenticator
{
	public function __construct(
		private Explorer $db,
	) {}

	public function authenticate(string $user, string $password): IIdentity
	{
		$row = $this->db->table('users')->where('email', $user)->fetch();

		if (!$row) {
			throw new AuthenticationException('Špatné údaje.');
		}

		if (!password_verify($password, $row->password_hash)) {
			throw new AuthenticationException('Špatné údaje.');
		}

		return new SimpleIdentity((int) $row->id, [$row->role], [
			'first_name' => $row->first_name,
			'last_name' => $row->last_name,
			'email' => $row->email,
		]);
	}
}
