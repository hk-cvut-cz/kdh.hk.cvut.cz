<?php

namespace App;

use Clevis\Skeleton\Entity;
use Nette\DateTime;


/**
 * @property App\Game $game {m:1 App\GamesRepository $votes}
 * @property Clevis\Users\User $user {m:1 Clevis\Users\UsersRepository $votes}
 * @property DateTime $createdAt {default now}
 */
class Vote extends Entity
{

}
