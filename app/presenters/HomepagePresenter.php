<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


/**
 * Homepage presenter.
 */
class HomepagePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->anyVariable = 'any value';
	}

}
