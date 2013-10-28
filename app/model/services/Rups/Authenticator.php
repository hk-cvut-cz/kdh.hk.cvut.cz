<?php

namespace App\Rups;

use Clevis\Users\UsersRepository;
use Nette\Object;
use Nette\Security;
use Orm\EntityToArray;


/**
 * Authenticator přes rups.hk.cvut.cz
 */
class Authenticator extends Object implements Security\IAuthenticator
{

	/** @var UsersRepository */
	private $users;

	/** @var PasswordHashCalculator */
	private $calculator;

	/** @var Repository */
	private $rups;


	public function __construct(UsersRepository $users, PasswordHashCalculator $calculator, Repository $rups)
	{
		$this->users = $users;
		$this->calculator = $calculator;
		$this->rups = $rups;
	}

	/**
	 * @param array
	 * @return Security\IIdentity
	 * @throws Security\AuthenticationException
	 */
	public function authenticate(array $args)
	{
		$username = $args[self::USERNAME];
		$password = $args[self::PASSWORD];

		$repo = $this->rups->getStaticRepository();

		if (!isset($repo[$username]))
		{
			throw new Security\AuthenticationException('Uživatelské jméno neexistuje.');
		}

		$rupsUser = $repo[$username];
		if (!$this->calculator->verify($password, $rupsUser->Login, $rupsUser->Password))
		{
			throw new Security\AuthenticationException('Nesprávné heslo.');
		}

		if (!$user = $this->users->getByRupsId($rupsUser->idUser))
		{
			$user = $this->users->insertFromRups($rupsUser);
		}

		return new Security\Identity($user->id, $user->roles, NULL);
	}

}
