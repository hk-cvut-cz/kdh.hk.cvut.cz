<?php

namespace App\Rups;

use Nette\Object;
use Nette\Utils\Strings;


/**
 * Zahashuje heslo stejně jako RUPS
 */
class PasswordHashCalculator extends Object
{

	/**
	 * Zahashuje heslo
	 *
	 * @param string
	 * @param string
	 * @return string
	 */
	public function hash($password, $login)
	{
		return sha1($login . $password);
	}

	/**
	 * Ověří heslo proti hashi
	 *
	 * @param string
	 * @param string
	 * @param string
	 * @return bool
	 */
	public function verify($password, $login, $hash)
	{
		return $this->hash($password, $login) === $hash;
	}

}
