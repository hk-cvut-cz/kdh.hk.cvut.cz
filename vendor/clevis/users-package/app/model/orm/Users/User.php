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
 */
class User extends Entity
{

}
