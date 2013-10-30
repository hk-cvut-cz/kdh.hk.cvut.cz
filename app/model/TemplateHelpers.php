<?php

namespace App;


class TemplateHelpers
{

	private static function timeToTimestap($time)
	{
		if (!$time) {
			return FALSE;
		} elseif (is_numeric($time)) {
			return (int) $time;
		} elseif ($time instanceof DateTime) {
			return $time->format('U');
		} else {
			return strtotime($time);
		}
	}

	public static function dateAgoInWords($time)
	{
		return self::timeAgoInWords($time, TRUE);
	}

	public static function timeAgoInWords($time, $mergeDays = FALSE)
	{
		if (!$time) {
			return FALSE;
		}
		$time = self::timeToTimestap($time);
		if ($mergeDays) {
			// toto je velmi ošklivý hack, který počítá s tím, že předané datumy mají nulový čas
			$time += 3600 * 23 + 3599;
		}

		$delta = time() - $time;

		if ($delta < 0) {
			$delta = round(abs($delta) / 60);
			if (!$mergeDays) {
				if ($delta == 0) return 'za okamžik';
				if ($delta == 1) return 'za minutu';
				if ($delta < 45) return 'za ' . $delta . ' ' . self::plural($delta, 'minuta', 'minuty', 'minut');
				if ($delta < 90) return 'za hodinu';
				if ($delta < 1440) return 'za ' . round($delta / 60) . ' ' . self::plural(round($delta / 60), 'hodina', 'hodiny', 'hodin');
			} else {
				if ($delta < 1440) return 'dnes';
			}
			if ($delta < 2880) return 'zítra';
			if ($delta < 43200) return 'za ' . round($delta / 1440) . ' ' . self::plural(round($delta / 1440), 'den', 'dny', 'dní');
			if ($delta < 86400) return 'za měsíc';
			if ($delta < 525960) return 'za ' . round($delta / 43200) . ' ' . self::plural(round($delta / 43200), 'měsíc', 'měsíce', 'měsíců');
			if ($delta < 1051920) return 'za rok';
			return 'za ' . round($delta / 525960) . ' ' . self::plural(round($delta / 525960), 'rok', 'roky', 'let');
		}

		$delta = round($delta / 60);
		if (!$mergeDays) {
			if ($delta == 0) return 'před okamžikem';
			if ($delta == 1) return 'před minutou';
			if ($delta < 45) return "před $delta minutami";
			if ($delta < 90) return 'před hodinou';
			if ($delta < 1440) return 'před ' . round($delta / 60) . ' hodinami';
		} else {
			if ($delta < 1440) return 'dnes';
		}
		if ($delta < 2880) return 'včera';
		if ($delta < 43200) return 'před ' . round($delta / 1440) . ' dny';
		if ($delta < 86400) return 'před měsícem';
		if ($delta < 525960) return 'před ' . round($delta / 43200) . ' měsíci';
		if ($delta < 1051920) return 'před rokem';
		return 'před ' . round($delta / 525960) . ' lety';
	}



	/**
	 * @param int $time
	 * @param string $format defaults to "Pondělí 1. ledna"
	 */
	public static function localeDate($time, $format = '%A	%e. %B')
	{
		$time = self::timeToTimestap($time);
		return strftime($format, $time);
	}



	/**
	 * Plural: three forms, special cases for 1 and 2, 3, 4.
	 * (Slavic family: Slovak, Czech)
	 * @param  int
	 * @return mixed
	 */
	public static function plural($n)
	{
		$args = func_get_args();
		return $args[($n == 1) ? 1 : (($n >= 2 && $n <= 4) ? 2 : 3)];
	}

}
