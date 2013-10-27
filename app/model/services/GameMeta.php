<?php

namespace App;

use Nette\Caching\Cache;
use Nette\Caching\Storages\FileStorage;
use Nette\Object;


class GameMeta extends Object
{

	/** @var Cache */
	private $cache;

	public function __construct(FileStorage $storage)
	{
		$this->cache = new Cache($storage, __CLASS__);
	}

	public function getByGameUrl($url)
	{
		$res = $this->cache->load($url);
		if ($res) {
			return $res;
		}

		$request = 'http://siteapi.eu/zatrolene-hry/?' . http_build_query(array('game' => $url));
		$res = json_decode(file_get_contents($request));

		$res->description = explode("\n", $res->description);

		$this->cache->save($url, $res, [
			Cache::EXPIRE => '+1 week',
		]);
		return $res;
	}

}
