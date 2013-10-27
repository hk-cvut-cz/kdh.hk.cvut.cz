<?php

namespace App;

use Clevis\Skeleton\Repository;


/**
 * @method Orm\IEntityCollection findAll()
 * @method Orm\IEntityCollection findProposedByVotes()
 */
class GamesRepository extends Repository
{

	public function getMetaRepository()
	{
		return $this->model->getContext()->getService('gameMeta');
	}

	public function findAvailable()
	{
		return $this->findAll()->where('status = ?', Game::AVAILABLE);
	}

}
