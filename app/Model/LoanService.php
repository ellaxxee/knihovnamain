<?php
declare(strict_types=1);

namespace App\Model;

use Nette\Database\Explorer;
use Nette\Security\User;

final class LoanService
{
	public function __construct(
		private Explorer $db,
		private User $user,
	) {}

	public function available(int $bookId): int
	{
		$book = $this->db->table('books')->get($bookId);
		
		$active = $this->db->table('loans')
			->where('book_id', $bookId)
			->where('returned_at IS NULL')
			->count('*');

		$free = (int) $book->total_copies - (int) $active;
		return $free > 0 ? $free : 0;
	}

	public function borrow(int $bookId): void
	{
		if (!$this->user->isLoggedIn()) {
			throw new \RuntimeException('Musíš být přihlášený.');
		}

		$mine = $this->db->table('loans')
			->where('user_id', $this->user->getId())
			->where('returned_at IS NULL')
			->count('*');

		if ($mine >= 3) {
			throw new \RuntimeException('Max 3 výpůjčky.');
		}

		

		$now = new \DateTimeImmutable();

		$this->db->table('loans')->insert([
			'user_id' => $this->user->getId(),
			'book_id' => $bookId,
			'loaned_at' => $now,
			'due_at' => $now->modify('+14 days'),
			'returned_at' => null,
		]);
	}

	public function returnLoan(int $loanId): void
	{
		

		$loan = $this->db->table('loans')->get($loanId);
	
		$loan->update(['returned_at' => new \DateTimeImmutable()]);
	}
}
