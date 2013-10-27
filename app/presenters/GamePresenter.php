<?php

namespace App;

use Clevis\Skeleton\BasePresenter;
use Nette;
use Nette\Application\UI\Form;
use Nette\DateTime;


class GamePresenter extends BasePresenter
{

	/**
	 * @persistent
	 */
	public $id;

	/** @var Game */
	private $game;

	public function startup()
	{
		$this->game = $this->context->orm->games->getById($this->id);
		if (!$this->game) {
			$this->error();
		}
		parent::startup();
	}

	public function renderDefault()
	{
		$this->template->game = $this->game;

		$disabledDays = [];
		$highlitDays = [];
		foreach ($this->game->upcomingReservations as $reservation)
		{
			$d = $reservation->date->format('U');
			if ($reservation->user === $this->userEntity)
			{
				$highlitDays[] = $d;
			}
			else
			{
				$disabledDays[] = $d;
			}
		}
		$this->template->disabledDays = $disabledDays;
		$this->template->highlitDays = $highlitDays;

		if ($this->user->loggedIn)
		{
			$this->template->reservations = $this->userEntity->reservations->get()
				->where('game_id = ?', $this->game->id)
				->where('date > Date(Now())')
				->orderBy('date', 'ASC');
		}
		else
		{
			$this->template->backlink = $this->storeRequest();
		}
	}

	public function createComponentReservation()
	{
		$modal = new Components\ModalDatePicker;
		$modal->setForm($this['reservationForm']);
		return $modal;
	}

	public function createComponentReservationForm($name)
	{
		$form = $this->createForm($name);

		$body = $form->addContainer('body');
		$body->addHidden('date');

		$buttons = $form->addContainer('buttons');
		$buttons->addSubmit('reserve', 'Rezervovat')
			->controlPrototype->class('btn btn-primary');

		return $form;
	}

	public function onSuccessReservationForm(Form $form)
	{
		try {
			$date = DateTime::from($form['body-date']->value);
		}
		catch (\Exception $e)
		{
			// failed to parse date, hackers playing
			return;
		}

		$today = new DateTime;
		$today->setTime(0, 0, 0);
		$max = DateTime::from('+ 1 month');
		try
		{
			if ($date < $today)
			{
				throw new ReservationException('Pokoušíte se rezervovat hru na datum v minulosti.');
			}
			elseif ($date > $max)
			{
				throw new ReservationException('Pokoušíte se rezervovat hru na datum v moc daleké budoucnosti.');
			}

			$this->game->reserve($this->userEntity, $date);
			$this->flashSuccess('Pokud nakonec hru nebudeš chtít, rezervaci zruš, ať je dostupná pro ostatní.', 'Rezervováno.');
			$this->redirect('this');
		}
		catch (ReservationException $e)
		{
			$this->flashError($e->getMessage());
			$this->redirect('this');
		}
	}

	public function createComponentDatePicker()
	{
		$picker = new Components\DatePicker;
		$picker->attachToControl($this['reservationForm-body-date']);
		return $picker;
	}

}
