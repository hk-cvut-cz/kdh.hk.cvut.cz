<?php

namespace App;

use Clevis\Skeleton\Mapper;
use Orm\IRepository;


class GamesMapper extends Mapper
{

	public function findProposedByVotes()
	{
		return $this->dataSource('
			SELECT [e].*
			FROM %n [e]', $this->getTableName(), '
			LEFT JOIN [votes] [v] ON [v.game_id]=[e.id]
			WHERE [e.status] = %s', Game::PROPOSED, '
			GROUP BY [e.id]
			ORDER BY Count([v.id]) DESC
		');
	}

}
