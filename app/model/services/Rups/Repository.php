<?php

namespace App\Rups;

use Clevis\Users\UsersRepository;
use Nette\Object;
use Nette\Security;
use Orm\EntityToArray;


class Repository extends Object
{

	const URL = 'http://kdh.hk.cvut.cz/rups-proxy.php';

	public function getStaticRepository()
	{
		$raw = file_get_contents(self::URL);
		$json = substr($raw, 1, -1);
		$data = json_decode($json)->results;

		$repo = [];
		foreach ($data as $row)
		{
			$repo[$row->Login] = $row;
		}
		return $repo;
	}

}
