<?php

namespace App;

use Clevis\Skeleton\Mapper;
use Orm\IRepository;


class VotesMapper extends Mapper
{

	public function findAll()
	{
		return $this->dataSource('
			SELECT [r].* FROM %n [r]', $this->getTableName(), '
			LEFT JOIN [games] [g] ON [r.game_id]=[g.id]
			WHERE [g.status] = %s', Game::PROPOSED
		);
	}

}
