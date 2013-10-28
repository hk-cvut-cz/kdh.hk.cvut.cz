<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->available = $this->orm->games->findAvailable()->orderBy('name');
		$this->template->purchased = $this->orm->games->findPurchased()->orderBy('name');
		$this->template->broken = $this->orm->games->findBroken()->orderBy('name');

		$this->template->reservations = [];
		if ($this->userEntity)
		{
			$this->template->reservations = $this->userEntity->reservations
				->get()->where('date > Date(Now())')->orderBy('date', 'ASC');
		}
	}

}
