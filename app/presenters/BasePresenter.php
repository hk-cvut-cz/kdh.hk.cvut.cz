<?php

namespace Clevis\Skeleton;

use App\Game;
use App\RepositoryContainer;
use Clevis\Skeleton\Core;
use Nette;
use Nette\Application\UI\Form;
use Nette\Utils\Html;
use StdClass;


/**
 * Base presenter for all application presenters
 *
 * @property-read RepositoryContainer $orm
 * @property StdClass $template
 */
abstract class BasePresenter extends Core\BasePresenter
{

	/**
	 * Returns RepositoryContainer.
	 *
	 * @author Jan Tvrdík
	 * @return RepositoryContainer
	 */
	public function getOrm()
	{
		return $this->context->getService('orm');
	}

	public function createForm($name)
	{
		$form = new Form;

		$form->onSuccess[] = callback($this, 'onSuccess' . ucfirst($name));

		return $form;
	}

	public function getUserEntity()
	{
		return $this->orm->users->getById($this->user->id);
	}

	public function beforeRender()
	{
		$this->template->userEntity = $this->userEntity;
	}

	public function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);
		$template->registerHelper('relativeDate', 'App\TemplateHelpers::dateAgoInWords');
		$template->registerHelper('relativeTime', 'App\TemplateHelpers::timeAgoInWords');
		$template->registerHelper('localDate', 'App\TemplateHelpers::localeDate');

		return $template;
	}

	public function flashMessage($message, $style = NULL, $title = NULL)
	{
		$flash = Html::el();
		$flash->add(Html::el('strong')->setText($title));
		$flash->add(Html::el('')->setHtml('&nbsp;'));
		$flash->add(Html::el('')->setText($message));
		parent::flashMessage($flash, $style);
	}

	public function flashSuccess($message, $title = NULL)
	{
		$this->flashMessage($message, 'success', $title);
	}

	public function flashError($message, $title = NULL)
	{
		$this->flashMessage($message, 'danger', $title);
	}

	public function handleCancel($reservation)
	{
		$reservation = $this->orm->reservations->getById($reservation);
		if (!$reservation)
		{
			$this->error();
		}
		elseif ($this->userEntity !== $reservation->user)
		{
			$this->deny();
		}

		if (!$reservation->canBeRemoved())
		{
			$this->deny();
		}

		$this->orm->reservations->remove($reservation);
		$this->orm->flush();
		$this->flashSuccess('Pokud si to rozmyslíš, klidně si hru zarezervuj znovu.', 'Zrušeno.');
		$this->redirect('this');
	}

	public function deny()
	{
		$this->error(NULL, Nette\Http\Response::S401_UNAUTHORIZED);
	}

}
