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

	const URL = 'http://kdh.hk.cvut.cz/rups-proxy.php';

	/** @var UsersRepository */
	private $users;

	/** @var PasswordHashCalculator */
	private $calculator;


	public function __construct(UsersRepository $users, PasswordHashCalculator $calculator)
	{
		$this->users = $users;
		$this->calculator = $calculator;
	}

	/**
	 * @todo add caching (2 min+ ?)
	 */
	public function getStaticRepository()
	{
		$raw = file_get_contents(self::URL);
		$json = substr($raw, 1, -1);
		$data = json_decode($json)->results;

		$repo = [];
		foreach ($data as $row)
		{
			$repo[$row->Login] = $row;
		}
		return $repo;
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

		$repo = $this->getStaticRepository();

		if (!isset($repo[$username]))
		{
			throw new Security\AuthenticationException('Username does not exist.');
		}

		$user = $repo[$username];
		if (!$this->calculator->verify($password, $user->Login, $user->Password))
		{
			throw new Security\AuthenticationException('Wrong password.');
		}

		return new Security\Identity($user->idUser, NULL, $user);
	}

}
