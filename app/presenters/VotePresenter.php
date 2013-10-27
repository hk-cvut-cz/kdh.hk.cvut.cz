<?php

namespace App;

use Clevis\Skeleton\BasePresenter;
use Nette;
use Nette\Application\UI\Form;
use Nette\DateTime;


class VotePresenter extends BasePresenter
{

	public function renderDefault()
	{
		$this->template->games = $this->orm->games->findProposedByVotes();

		$votedFor = [];
		if ($this->user->loggedIn) {
			foreach ($this->userEntity->votes as $vote)
			{
				$votedFor[] = $vote->game;
			}
		}
		$this->template->votedFor = $votedFor;
	}

	public function handleVote($game)
	{
		$votes = $this->userEntity->votes;
		$voteChanged = FALSE;
		if ($votes->count())
		{
			$voteChanged = TRUE;
			$votes->set([]);
		}

		$game = $this->orm->games->getById($game);
		$game->vote($this->userEntity);

		if ($voteChanged)
		{
			$this->flashSuccess('Vaše hlasování bylo změněno.');
		}
		else
		{
			$this->flashSuccess('Zahlasováno.');
		}
		$this->redirect('this');
	}

}
