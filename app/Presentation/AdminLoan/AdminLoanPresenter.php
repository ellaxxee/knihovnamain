<?php
declare(strict_types=1);

namespace App\Presentation\AdminLoan;

use App\Model\LoanService;
use App\Presentation\BasePresenter;
use Nette\Database\Explorer;


final class AdminLoanPresenter extends BasePresenter
{
	public function __construct(
		private Explorer $db,
		private LoanService $loanService,
	) {
		parent::__construct();
	}

	public function renderDefault(): void
	{
	
		$this->template->loans = $this->db->query('
			SELECT
				loans.id,
				loans.book_id,
				loans.user_id,
				loans.loaned_at,
				loans.due_at,
				loans.returned_at,
				books.title AS book_title,
				users.first_name,
				users.last_name,
				users.email
			FROM loans
			JOIN books ON books.id = loans.book_id
			JOIN users ON users.id = loans.user_id
			ORDER BY loans.returned_at IS NULL DESC, loans.due_at ASC
')->fetchAll();
	}


}
