<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->games = $this->orm->games->findAll();
		$this->template->reservations = $this->userEntity->reservations->get()->orderBy('date', 'ASC');
	}

}
