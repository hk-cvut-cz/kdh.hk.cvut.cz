<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->games = $this->context->orm->games->findAll();
	}

}
