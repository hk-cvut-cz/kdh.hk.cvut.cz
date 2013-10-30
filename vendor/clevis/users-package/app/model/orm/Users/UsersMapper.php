<?php

namespace Clevis\Users;

use Clevis\Skeleton\Mapper;
use Orm;


/**
 * Maps User entities to database.
 */
class UsersMapper extends Mapper
{

	public function createManyToManyMapper($param, Orm\IRepository $targetRepository, $targetParam)
	{
		$map = parent::createManyToManyMapper($param, $targetRepository, $targetParam);
		if ($param === 'gamesSearching')
		{
			$map->table = 'searching';
			$map->childParam = 'game_id';
			$map->parentParam = 'user_id';
		}
		return $map;
	}

}
