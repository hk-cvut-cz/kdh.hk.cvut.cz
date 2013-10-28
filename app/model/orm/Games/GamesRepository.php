<?php

namespace App;

use Clevis\Skeleton\Repository;


/**
 * @method Orm\IEntityCollection findAll()
 * @method Orm\IEntityCollection findByStatus()
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
		return $this->findByStatus(Game::AVAILABLE);
	}

	public function findPurchased()
	{
		return $this->findByStatus(Game::PURCHASED);
	}

	public function findBroken()
	{
		return $this->findByStatus(Game::BROKEN);
	}

}
