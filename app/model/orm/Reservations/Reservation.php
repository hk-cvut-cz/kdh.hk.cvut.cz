<?php

namespace App;

use Clevis\Skeleton\Entity;
use Nette\DateTime;


/**
 * @property App\Game $game {m:1 App\GamesRepository $reservations}
 * @property Clevis\Users\User $user {m:1 Clevis\Users\UsersRepository $reservations}
 * @property DateTime $date
 */
class Reservation extends Entity
{

	public function canBeRemoved()
	{
		$today = new DateTime;
		$today->setTime(0, 0, 0);
		return $this->date >= $today;
	}

}

class ReservationException extends \Exception {}
