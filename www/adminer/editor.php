<?php

/**
 * Nette user login for Adminer Editor
 * @author Mikuláš Dítě
 * @license BSD-3
 *
 * Expects the following config.neon setup:
 * common:
 *	parameters:
 *		database:
 *			host: localhost
 *			database: yourdbname
 *			username: youruser
 *			password: yourpass
 *		adminerEditor:
 *			login: []
 *	nette:
 *		database:
 *			default:
 *				dsn: "mysql:host=%database.host%;dbname=%database.dbname%"
 *					user: %database.user%
 *					password: %database.password%
 */

$container = require __DIR__ . '/../../app/bootstrap.php';
$user = $container->orm->users->getById($container->user->id);

$_GET['username'] = ''; // triggers autologin

function adminer_object() {

	include_once __DIR__ . "/plugins/plugin.php";

    // autoloader
    foreach (glob("plugins/*.php") as $filename) {
		include_once "./$filename";
    }

    $plugins = array(
		// specify enabled plugins here
		new AdminerTranslation,
		new AdminerVersionNoverify,
    );

	class AdminerSoftware extends AdminerPlugin {

		private $context;
		private $user;

		function __construct($plugins, $context, $user) {
			parent::__construct($plugins);
			$this->context = $context;
			$this->user = $user;
		}

		function name() {
			// custom name in title and heading
			return 'KDH';
		}

		function credentials() {
			// server, username and password for connecting to database
			$c = $this->context->parameters['database'];
			return array($c['host'], $c['username'], $c['password']);
		}

		function database() {
			// database name, will be escaped by Adminer
			return $this->context->parameters['database']['database'];
		}

		function login() {
			return $this->isLoggedIn() && $this->isInRole();
		}

		function tableName($tableStatus) {
			// only tables with comment will be displayed
			return h($tableStatus["Comment"]);
		}

		function fieldName($field, $order = 0) {
			// only columns with comments will be displayed
			// table must have at least one column with comment
			// to select properly
			return h($field["comment"]);
		}

		function loginForm() {
			if (!$this->isLoggedIn()) {
				echo "<p>Přihlaste se prosím ke svému účtu přes tradiční formulář.</p>";
			} else if (!$this->isInRole()) {
				echo "<p>Váš účet nemá oprávnění k editoru.</p>";
			}
		}

		private function isLoggedIn() {
			return $this->context->user->isLoggedIn();
		}

		private function isInRole() {
			return in_array(
				$this->user->login,
				$this->context->parameters['adminerEditor']['login']
			);
		}

	}

	global $container;
	global $user;
	return new AdminerSoftware($plugins, $container, $user);
}

include "./editor-3.7.1-mysql.php";
