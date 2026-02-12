<?php
declare(strict_types=1);

namespace App\Presentation\Book;

use App\Model\LoanService;
use App\Presentation\BasePresenter;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use Nette\Http\FileUpload;

final class BookPresenter extends BasePresenter
{
	public function __construct(
		private Explorer $db,
		private string $wwwDir,
		private LoanService $loanService,
	) {
		parent::__construct();
	}

	public function renderDefault(): void
	{
		$books = $this->db->table('books')->order('added_at DESC')->fetchAll();
		$this->template->books = $books;

		$available = [];
		foreach ($books as $b) {
			$available[(int) $b->id] = $this->loanService->available((int) $b->id);
		}
		$this->template->available = $available;
	}

	public function renderDetail(int $id): void
	{
		$book = $this->db->table('books')->get($id);

		$this->template->book = $book;
		$this->template->available = $this->loanService->available($id);
	}

	public function actionAdd(): void
	{
		if (!$this->getUser()->isInRole('admin')) {
			$this->error('Forbidden', IResponse::S403_FORBIDDEN);
		}
	}

	public function actionEdit(int $id): void
	{

		$book = $this->db->table('books')->get($id);

		$this['bookForm']->setDefaults($book->toArray());
	}

	protected function createComponentBookForm(): Form
	{
		$form = new Form;

		$form->addHidden('id');
		$form->addText('title', 'Název:')->setRequired();
		$form->addText('author', 'Autor:')->setRequired();
		$form->addTextArea('description', 'Popis:')->setRequired();
		$form->addText('isbn', 'ISBN:')->setRequired();
		$form->addInteger('publication_year', 'Rok:')->setRequired();
		$form->addText('genre', 'Žánr:')->setRequired();
		$form->addInteger('total_copies', 'Celkem kusů:')->setRequired();
		$form->addUpload('cover', 'Obálka:')->setRequired();

		$form->addSubmit('send', 'Uložit');

		$form->onSuccess[] = [$this, 'bookFormSucceeded'];
		return $form;
	}

	public function bookFormSucceeded(Form $form, \stdClass $v): void
	{

		$data = [
			'title' => $v->title,
			'author' => $v->author,
			'description' => $v->description,
			'isbn' => $v->isbn,
			'publication_year' => $v->publication_year,
			'genre' => $v->genre,
			'total_copies' => $v->total_copies,
		];
			 
		$cover = $v->cover;
		if ($cover instanceof FileUpload && $cover->isOk()) {
			//cilove slozka coveru nan zobrazeni v browsru
			$dir = $this->wwwDir . '/images/covers';
			// random string aby se soubory neprepsaly
			$name = bin2hex(random_bytes(8)) . '_' . $cover->getSanitizedName();
			//soubor se ulozi do slozky
			$cover->move($dir . '/' . $name);
			//relativni cesta do db pro latte ${$basePath}/{$book->cover})
			$data['cover'] = 'images/covers/' . $name;
		}

		if ($v->id) {
			$this->db->table('books')->get((int) $v->id)?->update($data);
		} else {
			$data['added_at'] = new \DateTimeImmutable();
			$this->db->table('books')->insert($data);
		}

		$this->redirect('Book:default');
	}

	public function handleBorrow(int $id): void
	{
		try {
			//loan service - bowrrow podminky
			$this->loanService->borrow($id);
			$this->flashMessage('Vypůjčeno.');
			// chyti exceptions z loan service a vyhodi podle toho zpravu
		} catch (\Throwable $e) {
			$this->flashMessage($e->getMessage());
		}
		$this->redirect('this');
	}

	public function handleDelete(int $id): void
	{
		if (!$this->getUser()->isInRole('admin')) 
		$this->db->table('books')->get($id)?->delete();
		$this->redirect('Book:default');
	}
}
