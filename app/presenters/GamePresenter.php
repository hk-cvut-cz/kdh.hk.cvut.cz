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

	public function handleLookForPlayers()
	{
		// @TOOD add email to queue for all players searching (only if count>=2)
		$this->game->playersSearching->add($this->userEntity);
		$this->orm->flush();
		$count = $this->game->playersSearching->count();
		if ($count >= 2)
		{
			$other = $count - 1;
			$plural = TemplateHelpers::plural($other, 'další hráč', 'další hráči', 'dalších hráčů');
			$word = $count == 2 ? 'oba' : 'všichni';
			$this->flashSuccess("Tuhle hru chce hrát $other $plural, $word teď dostanete email a můžete se domluvit (nebo počkat na další hráče, pokud je vás málo).", 'Super!'); // @todo pluralize
		}
		else
		{
			$this->flashSuccess('Zatím se ke hraní této hry nikdo jiný nepřihlásil. Hned jak se někdo další přidá, přijde ti email.', 'Patience young padawan.');
		}
		$this->redirect('this');
	}

	public function handleStopLookingForPlayers()
	{
		$this->game->playersSearching->remove($this->userEntity);
		$this->orm->flush();
		$count = $this->game->playersSearching->count();
		if ($count == 0)
		{
			$this->flashSuccess('Zatím se ke hraní nikdo nepřihlásil, ale třeba se ještě někdo najde.', 'Nerozmyslíš si to?');
		}
		else
		{
			$this->flashSuccess('Ostatním uživatelům nebude odeslán žádný email o tom, že už spoluhráče nehledáš.', 'Hotovo.');
		}
		$this->redirect('this');
	}

}
