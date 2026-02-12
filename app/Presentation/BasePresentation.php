<?php
declare(strict_types=1);

namespace App\Presentation;

use Nette\Application\UI\Presenter;

abstract class BasePresenter extends Presenter
{
	protected function startup(): void
	{
		parent::startup();

		// TÍMTO NATVRDO ŘEKNEŠ:
		// „VŠECHNY STRÁNKY POUŽÍVEJ TENHLE LAYOUT“
		$this->setLayout(__DIR__ . '/@layout.latte');
	}
}
