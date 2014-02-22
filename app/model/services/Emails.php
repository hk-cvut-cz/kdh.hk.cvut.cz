<?php

namespace App;

use Clevis\Users\User;
use Nette\Mail\Message;
use Nette;


class Emails
{

	private $mailer;

	public function __construct(Nette\Mail\IMailer $mailer)
	{
		$this->mailer = $mailer;
	}

	public function notifyPlayersSearching(Game $game, User $newPlayer)
	{
		$players = $game->playersSearching;

		$template = new Nette\Templating\FileTemplate(__DIR__ . '/../../templates/emails/notifyPlayersSearching.latte');
		$template->registerFilter(new Nette\Latte\Engine);
		$template->registerHelperLoader('Nette\Templating\Helpers::loader');
		$template->stylePath = __DIR__ . '/../../templates/emails/bootstrap-email.min.css';

		$template->game = $game;
		$template->players = $players;
		$template->newPlayer = $newPlayer;

		$mail = new Message;
		$mail->setFrom('Klub Deskových Her <kdh@hk.cvut.cz>')
			->setHtmlBody($template);

		foreach ($players as $player)
		{
			$mail->addTo($player->fullName . " <$player->email>");
		}

		$this->mailer->send($mail);
	}

}
