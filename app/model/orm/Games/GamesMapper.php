<?php

namespace App;

use Clevis\Skeleton\Mapper;
use Orm\EventArguments;
use Orm\IRepository;


class GamesMapper extends Mapper
{

	public function __construct(IRepository $repository)
	{
		parent::__construct($repository);
		$events = $repository->events;
		$events->addCallbackListener($events::HYDRATE_AFTER, function(EventArguments $args) {
			$e = $args->entity;
			$change = FALSE;
			if (!$args->data['tags']) {
				$e->tags = implode(', ', $e->meta->categories);
				$change = TRUE;
			}
			if (!$args->data['time']) {
				$e->time = $e->meta->playtime;
				$change = TRUE;
			}
			if (!$args->data['text']) {
				$desc = $e->meta->description;
				array_walk($desc, 'trim');
				$e->text = implode("\n\n", array_filter($desc));
				$change = TRUE;
			}
			foreach (['players', 'publisher', 'published', 'rating', 'cover'] as $key)
			{
				if (!$args->data[$key]) {
					$e->$key = $e->meta->$key;
					$change = TRUE;
				}
			}

			if ($change)
			{
				$this->model->flush();
			}
		});
	}

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
