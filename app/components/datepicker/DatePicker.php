<?php

namespace App\Components;

use Nette\Forms\IControl;
use Nette\DateTime;
use DateInterval;


class DatePicker extends BaseControl
{

	private $control;

	public function render($disableDays = [], $highlitDays = [])
	{
		if (!$this->control)
		{
			throw new Exception;
		}
		$this->template->control = $this->control;

		$start = DateTime::from('last monday');
		$end = DateTime::from('+ 1 month');
		$this->template->calendar = $this->buildCalendar($start, $end, $disableDays, $highlitDays);

		$this->template->render();
	}

	public function renderScript()
	{
		$this->template->setFile(__DIR__ . '/script.latte');
		$this->template->id = $this->control->htmlId;
		$this->template->render();
	}

	private function buildCalendar(DateTime $start, DateTime $end, $disabledDays = [], $highlitDays = [])
	{
		$calendar = [];

		$today = new DateTime;
		$today->setTime(0, 0, 0);

		$day = DateInterval::createFromDateString('1 day');
		$date = $start;

		$row = [];
		while ($date < $end)
		{
			$state = $date < $today ? 'old' : ($date->format('U') == $today->format('U') ? 'active' : '');
			if (in_array($date->format('U'), $disabledDays))
			{
				$state = 'disabled';
			}
			else if (in_array($date->format('U'), $highlitDays))
			{
				$state = 'disabled highlit';
			}

			$row[] = (object) [
				'date' => clone $date,
				'state' => $state,
			];
			if (count($row) === 7)
			{
				$calendar[] = $row;
				$row = [];
			}
			$date->add($day);
		}
		if (count($row)) {
			$calendar[] = $row;
		}
		return $calendar;
	}

	public function attachToControl(IControl $control)
	{
		$this->control = $control;
	}

}
