<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->games = $this->orm->games->findAvailable()->orderBy('name');

		$this->template->reservations = [];
		if ($this->userEntity)
		{
			$this->template->reservations = $this->userEntity->reservations
				->get()->where('date > Date(Now())')->orderBy('date', 'ASC');
		}
	}

}
