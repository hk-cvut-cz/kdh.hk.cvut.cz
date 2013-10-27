<?php

namespace Clevis\Users;

use Clevis\Skeleton\Repository;


/**
 * Repository containing users
 *
 * @method User getById($id)
 * @method User getByRupsId($id)
 */
class UsersRepository extends Repository
{

	public function insertFromRups($data)
	{
		$user = new User();
		$user->rupsId = $data->idUser;
		$user->login = $data->Login;
		$user->name = $data->Name;
		$user->surname = $data->Surname;
		$user->email = $data->email;

		$this->attach($user);
		$this->flush();
		return $user;
	}

}
