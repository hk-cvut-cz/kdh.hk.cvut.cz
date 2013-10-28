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
 * @property Orm\MacroSet $roles {enum self::getRoles()}
 */
class User extends Entity
{

	const ROLE_EDITOR = 'editor';

	public static function getRoles()
	{
		return [
			self::ROLE_EDITOR,
		];
	}

}
