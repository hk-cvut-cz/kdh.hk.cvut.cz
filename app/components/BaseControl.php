<?php

namespace App\Components;

use Clevis\Skeleton;


abstract class BaseControl extends Skeleton\BaseControl
{

	public function createTemplate($class = NULL)
	{
		$template = parent::createTemplate($class);

		$reflex = new \ReflectionClass($this);
		$dir = dirname($reflex->getFileName());
		$template->setFile($dir . '/template.latte');

		return $template;
	}

}
