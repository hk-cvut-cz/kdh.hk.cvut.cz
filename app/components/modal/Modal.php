<?php

namespace App\Components;

use Nette\Application\UI\Form;


class Modal extends BaseControl
{

	/** @var Form */
	protected $form;

	public function render($title)
	{
		$this->template->title = $title;
		$this->template->form = $this->form;
		$this->template->setFile(__DIR__ . '/template.latte');
		$this->template->render();
	}

	public function renderButton($label)
	{
		$this->template->label = $label;
		$this->template->setFile(__DIR__ . '/button.latte');
		$this->template->render();
	}

	public function setForm(Form $form)
	{
		$this->form = $form;
	}

}
