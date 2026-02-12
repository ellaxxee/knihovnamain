<?php
declare(strict_types=1);

namespace App\Presentation\Sign;

use App\Model\UserService;
use Nette;
use Nette\Application\UI\Form;
use App\Presentation\BasePresenter;

final class SignPresenter extends BasePresenter
{
	public function __construct(
		private UserService $userService,
	) {
		parent::__construct();
	}

	protected function createComponentSignInForm(): Form
	{
		$form = new Form;

		$form->addEmail('email', 'E-mail:')
			->setRequired('Zadej e-mail.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadej heslo.');

		$form->addSubmit('send', 'Přihlásit');

		$form->onSuccess[] = $this->signInFormSucceeded(...);
		return $form;
	}

	private function signInFormSucceeded(Form $form, \stdClass $data): void
	{
		try {
			$this->getUser()->login($data->email, $data->password);
			$this->flashMessage('Přihlášení bylo úspěšné.', 'success');
			$this->redirect('Book:default');
		} catch (Nette\Security\AuthenticationException $e) {
			$this->flashMessage('Nesprávný e-mail nebo heslo.', 'error');
			$this->redirect('this');
		}
	}

	protected function createComponentRegisterForm(): Form
	{
		$form = new Form;

		$form->addText('username', 'Uživatelské jméno:')
			->setRequired('Zadej uživatelské jméno.');

		$form->addText('first_name', 'Jméno:')
			->setRequired('Zadej jméno.');

		$form->addText('last_name', 'Příjmení:')
			->setRequired('Zadej příjmení.');

		$form->addEmail('email', 'E-mail:')
			->setRequired('Zadej e-mail.');

		$form->addPassword('password', 'Heslo:')
			->setRequired('Zadej heslo.');

		$form->addSubmit('send', 'Registrovat');

		$form->onSuccess[] = function (Form $form, \stdClass $data): void {
			try {
				$this->userService->register(
					$data->email,
					$data->password,
					$data->username,
					$data->first_name,
					$data->last_name,
					'student',
				);

				$this->flashMessage('Registrace OK. Teď se přihlas.', 'success');
				$this->redirect('Sign:in');
			} catch (\Throwable $e) {
				$this->flashMessage($e->getMessage(), 'error');
				$this->redirect('this');
			}
		};

		return $form;
	}

	public function actionOut(): void
	{
		$this->getUser()->logout(true);
		$this->flashMessage('Odhlášení bylo úspěšné.');
		$this->redirect('Book:default');
	}
}