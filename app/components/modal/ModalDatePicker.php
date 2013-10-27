<?php

namespace App\Components;


class ModalDatePicker extends Modal
{

	public function render($title, $disabledDays = [], $highlitDays = [])
	{
		$this->template->title = $title;
		$this->template->disabledDays = $disabledDays;
		$this->template->highlitDays = $highlitDays;
		$this->template->form = $this->form;
		$this->template->setFile(__DIR__ . '/template_datepicker.latte');
		$this->template->render();
	}

	public function createComponentDatePicker()
	{
		$picker = new DatePicker;
		$picker->attachToControl($this->form['body-date']);
		return $picker;
	}

}
