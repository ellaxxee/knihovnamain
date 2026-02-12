<?php
declare(strict_types=1);

namespace App\Presentation\Loan;

use App\Model\LoanService;
use App\Presentation\BasePresenter;
use Nette\Database\Explorer;

final class LoanPresenter extends BasePresenter
{
	public function __construct(
		private Explorer $db,
		private LoanService $loanService,
	) {
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$id = (int) $this->getUser()->getId();

		$this->template->loans = $this->db->query('
			SELECT
				loans.id,
				loans.book_id,
				loans.loaned_at,
				loans.due_at,
				loans.returned_at,
				books.title AS book_title,
				books.author AS book_author,
				books.cover AS book_cover
			FROM loans
			JOIN books ON books.id = loans.book_id
			WHERE loans.user_id = ?
			ORDER BY loans.loaned_at DESC
', $id)->fetchAll();
	}

	public function handleReturn(int $id): void
{
    $this->loanService->returnLoan($id);
    $this->flashMessage('Vráceno.');
    $this->redirect('this');
}
}
