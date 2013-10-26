<?php

namespace App;

use Nette\Object;


class GameMeta extends Object
{

	/**
	 * @todo cache
	 */
	public function getByGameUrl($url)
	{
		$request = 'http://siteapi.eu/zatrolene-hry/?' . http_build_query(array('game' => $url));
		return json_decode(file_get_contents($request));
	}

}
