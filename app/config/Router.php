<?php

namespace App;

use Nette\DI\Container;
use Nette\Application\Routers\Route;
use Nette\Application\Routers\RouteList;


/**
 * Routování
 */
class Router extends RouteList
{

	public function __construct(Container $context)
	{
		parent::__construct();

		$this[] = new Route('index.php', 'Homepage:default', Route::ONE_WAY);
		$this[] = new Route('<presenter>/<action>[/<id>]', 'Homepage:default');
	}

}
