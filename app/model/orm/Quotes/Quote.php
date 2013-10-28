<?php

namespace App;

use Clevis\Skeleton\Entity;
use Nette\DateTime;


/**
 * @property string $quote
 * @property string $author
 */
class Quote extends Entity
{

	public function getText()
	{
		$text = '„' . $this->quote . '“';
		if ($this->author)
		{
			$text .= ' — ' . $this->author;
		}
		return $text;
	}

	public function __toString()
	{
		return $this->getText();
	}

}
