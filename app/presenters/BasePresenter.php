<?php

namespace Clevis\Skeleton;

use App\Game;
use App\RepositoryContainer;
use Clevis\Skeleton\Core;
use Nette;
use Nette\Application\UI\Form;
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

	public function flashSuccess($message)
	{
		$this->flashMessage($message, 'success');
	}

	public function flashError($message)
	{
		$this->flashMessage($message, 'danger');
	}

	public function handleCancel($reservation)
	{
		$reservation = $this->orm->reservations->getById($reservation);
		if ($this->userEntity !== $reservation->user) {
			$this->error(); // @todo throw denied not 404
		}

		$this->orm->reservations->remove($reservation);
		$this->orm->flush();
		$this->flashSuccess('Rezervace byla zrušena.');
		$this->redirect('this');
	}

}
