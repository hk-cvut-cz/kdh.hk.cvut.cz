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

}
