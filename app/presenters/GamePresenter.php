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

		$this->template->reservations = $this->userEntity->reservations->get()
			->where(['game = ?' => $this->game->id])->orderBy('date', 'ASC');
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
		// @TODO how many reservations user has
		// @TODO check if reservation date is valid
		try
		{
			$date = DateTime::from($form['body-date']->value);
			$this->game->reserve($this->userEntity, $date);
			$this->flashSuccess('Reserved');
			$this->redirect('this');
		}
		catch (ReservationException $e)
		{
			$this->flashError($e->getMessage());
		}
	}

	public function createComponentDatePicker()
	{
		$picker = new Components\DatePicker;
		$picker->attachToControl($this['reservationForm-body-date']);
		return $picker;
	}

}
