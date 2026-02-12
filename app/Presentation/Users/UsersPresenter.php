<?php

declare(strict_types=1);

namespace App\Presentation\Users;

use Nette;
use Nette\Application\UI\Form;
use Nette\Database\Explorer;
use App\Presentation\BasePresenter;

final class UsersPresenter extends BasePresenter

{
    private Explorer $database;

    public function __construct(Explorer $database)
    {        $this->database = $database;
        
    }
    public function renderDefault(): void
    {
        // pripojeni na lattecko a nacteni dat z tabulky users
        $this->template->users = $this->database->table('users')->fetchAll();
    }
        // uprava uzivatele podle id

    public function actionEdit(int $id): void

{   //predvyplni data pro edit z formulare podle id
    $user = $this->database->table('users')->get($id);
    $this['userForm']->setDefaults($user->toArray());
}

 protected function createComponentUserForm(): Form
{
    $form = new Form;

    $form->addText('username', 'Username:')
        ->setRequired('Add a username.');

    $form->addText('first_name', 'Name:')
        ->setRequired('Add a name.');

    $form->addText('last_name', 'Last name:')
        ->setRequired('Add a last name.');

    $form->addEmail('email', 'E-mail:')
        ->setRequired('Add an e-mail.');

   $form->addPassword('password', 'Heslo:')
    ->setNullable()
    ->addCondition(Form::FILLED)
        ->addRule(Form::MIN_LENGTH, 'Heslo musí mít alespoň 6 znaků', 6);

    $form->addSelect('role', 'Role:', [
    'user' => 'User',
    'admin' => 'Admin',
    'student' => 'Student',  
])->setPrompt('Select role');

    $form->addText('created_at', 'Created at:')
        ->setDefaultValue(date('Y-m-d H:i:s'))
        ->setDisabled();

    $form->addSubmit('send', 'Save');
    // kdyz se formular odesle, zavola metodu userFormSucceeded
    $form->onSuccess[] = [$this, 'userFormSucceeded'];

    return $form;
}
public function userFormSucceeded(Form $form, \stdClass $values): void
{
        // url ziska id parametru
    $id = $this->getParameter('id');

        // priprava dat pro vlozeni nebo upravu do databaze 
  $data = [
    'username'      => $values->username,
    'first_name'    => $values->first_name,
    'last_name'     => $values->last_name,
    'email'         => $values->email,
    'password_hash' => password_hash($values->password, PASSWORD_DEFAULT),
    'role'          => $values->role,
];

    // id exsituje - prida nove hodnoty do stejneho id 
    if ($id) {
        $this->database->table('users')->get($id)?->update($data);
        $this->flashMessage('Uživatel byl upraven.', 'success');

    // id neexsituje - vytvori nove id s casem vytvoreni

    } else {
        $data['created_at'] = date('Y-m-d H:i:s');
        $this->database->table('users')->insert($data);
        $this->flashMessage('Nový uživatel byl vytvořen.', 'success');
    }
    // presmerovani na defaultni stranku presenteru - refresh s updejtlymi daty
    $this->redirect('default');
}
// mazani uzivatele podle filtru id 
    public function handleDelete(int $id): void
    {
        $this->database->table('users')->where('id', $id)->delete();
        $this->flashMessage('The user has been deleted.', 'info');
        $this->redirect('this');
    }
}