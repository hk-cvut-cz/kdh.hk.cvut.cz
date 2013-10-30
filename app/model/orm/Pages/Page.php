<?php

namespace App;

use Clevis\Skeleton\Entity;
use Clevis\Users\User;
use Nette\DateTime;
use dflydev\markdown\MarkdownExtraParser;


/**
 * @property string $name
 * @property string $markdown
 */
class Page extends Entity
{

	/**
	 * @return string html
	 */
	public function getHtml()
	{
		$md = new MarkdownExtraParser();
		return $md->transformMarkdown($this->markdown);
	}



	public function __toString()
	{
		return $this->getHtml();
	}

}
