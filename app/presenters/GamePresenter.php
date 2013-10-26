<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class GamePresenter extends BasePresenter
{

	/**
	 * @persistent
	 */
	public $id;

	/**
	 * @param int $id
	 */
	public function renderDefault()
	{
		$game = $this->context->orm->games->getById($this->id);
		if (!$game) {
			$this->error();
		}
		$this->template->game = $game;
	}

}
