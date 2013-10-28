<?php

namespace App;

use Clevis\Skeleton\Mapper;
use Orm\IRepository;


class QuotesMapper extends Mapper
{

	public function getRandom()
	{
		return $this->dataSource('
			SELECT * FROM %n [e] ', $this->getTableName(), '
			ORDER BY Rand()
			LIMIT 1
		')->fetch();
	}

}
