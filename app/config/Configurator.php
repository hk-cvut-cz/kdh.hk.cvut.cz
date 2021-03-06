<?php

namespace App;

use Nette;
use Nette\DI;
use Nette\Loaders\RobotLoader;
use Nette\FileNotFoundException;
use RuntimeException;
use SystemContainer;


/**
 * Nastavení aplikace (nahrazuje boostrap)
 *
 * @method void onInit
 * @method void onAfter
 */
class Configurator extends Nette\Configurator
{

	/** @var array of function(Configurator $sender); Occurs before first Container is created */
	public $onInit = array();

	/** @var array of function(Configurator $sender); Occurs after first Container is created */
	public $onAfter = array();


	/**
	 * @param string|NULL null mean autodetect
	 * @param array|NULL
	 */
	public function __construct($tempDirectory = NULL, array $params = NULL)
	{
		parent::__construct();

		if ($tempDirectory === NULL) {
			$tempDirectory = realpath(__DIR__ . '/../../temp');
		}
		$this->addParameters((array) $params + array_map('realpath', array(
			'appDir' => __DIR__ . '/..',
			'libsDir' => __DIR__ . '/../../vendor',
			'wwwDir' => __DIR__ . '/../../www',
		)));
		$this->setTempDirectory($tempDirectory);

		foreach (get_class_methods($this) as $name) {
			if ($pos = strpos($name, 'onInit') === 0 && $name !== 'onInitPackages') {
				$this->onInit[lcfirst(substr($name, $pos + 5))] = array($this, $name);
			}
		}

		foreach (get_class_methods($this) as $name) {
			if ($pos = strpos($name, 'onAfter') === 0) {
				$this->onAfter[lcfirst(substr($name, $pos + 5))] = array($this, $name);
			}
		}
	}


	/**
	 * Zaregistruje balíčky. Musí být načteny jako první
	 */
	public function onInitPackages()
	{
		\Clevis\Users\Package::register($this);
	}


	/**
	 * Zaregistruje konfigurační soubory
	 */
	public function onInitConfigs()
	{
		$params = $this->getParameters();
		$this->addConfig($params['appDir'] . '/config/config.neon', FALSE);
		$this->addConfig($params['appDir'] . '/config/config.local.neon', FALSE);
	}


	/**
	 * Zaregistruje rozšíření konfigurace
	 */
	public function onInitExtensions()
	{
		$this->onCompile['dibi'] = function ($configurator, DI\Compiler $compiler) {
			$compiler->addExtension('dibi', new \DibiNette21Extension);
		};
	}

	/**
	 * Zaregistruje moduly
	 */
	public function onInitModules()
	{
		//App\Some\Module::register($this);
	}


	/**
	 * Zaregistruje relace entit
	 */
	public function onAfterEntities(SystemContainer $container)
	{
		$registry = $container->getService('entityRelationsRegistry');
		\Clevis\Skeleton\Entity::$relationsRegistry = $registry;
	}


	/**
	 * Vytvoří RobotLoader a zaregistruje adresář 'app'
	 *
	 * @return RobotLoader
	 */
	public function createRobotLoader()
	{
		$params = $this->getParameters();
		$loader = parent::createRobotLoader();
		$loader->addDirectory($params['appDir']);
		$loader->addDirectory($params['libsDir'] . '/others');

		return $loader;
	}


	/**
	 * @return array
	 */
	public function getParameters()
	{
		return $this->parameters;
	}


	/**
	 * Vytvoří systémový DI kontejner
	 *
	 * @return SystemContainer
	 */
	public function createContainer()
	{
		$this->onInitPackages($this);
		$this->onInit($this);
		$this->onInit = array();

		try {
			$container = parent::createContainer();
			$this->onAfter($container);

			return $container;
		}
		catch (FileNotFoundException $e)
		{
			if (strpos($e->getMessage(), 'local') !== FALSE)
			{
				throw new MissingLocalConfigException($e);
			}
			else
			{
				throw $e;
			}
		}
	}

}


/**
 * Chybějící konfigurační soubor
 */
class MissingLocalConfigException extends RuntimeException
{

	/**
	 * @param  FileNotFoundException
	 */
	public function __construct(FileNotFoundException $e)
	{
		parent::__construct('Pro spuštění aplikace si do složky "app/config" doplň konfigurační soubor "config.local.neon". Můžeš za tím účelem zkopírovat "config.local.example.neon", který se nenačítá a slouží jako vzor.', NULL, $e);
	}

}
