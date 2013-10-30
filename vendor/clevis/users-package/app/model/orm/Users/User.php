<?php

namespace Clevis\Users;

use Clevis\Skeleton\Entity;
use Orm\EntityNotPersistedException;


/**
 * @property int $rupsId
 * @property string $login
 * @property string $name
 * @property string $surname
 * @property string $email
 * @property Orm\OneToMany $reservations {1:m App\ReservationsRepository $user}
 * @property Orm\OneToMany $votes {1:m App\VotesRepository $user}
 * @property string $role {enum self::getRoles()}
 * @property Orm\ManyToMany $gamesSearching {m:m App\GamesRepository $playersSearching map}
 */
class User extends Entity
{

	const ROLE_EDITOR = 'editor';

	public static function getRoles()
	{
		return [
			self::ROLE_EDITOR => self::ROLE_EDITOR,
		];
	}

}
