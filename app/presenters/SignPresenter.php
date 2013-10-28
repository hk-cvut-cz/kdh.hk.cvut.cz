<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class SignPresenter extends BasePresenter
{

	/** @persistent */
	public $backlink;

	public function createComponentSignInForm($name)
	{
		if ($this->user->loggedIn)
		{
			$this->redirect('Homepage:');
		}

		$form = $this->createForm($name);

		$form->addText('username', 'Uživatelské jméno')
			->setRequired();
		$form->addPassword('password', 'Heslo')
			->setRequired();
		$form->addSubmit('login', 'Přihlásit se');

		return $form;
	}

	public function onSuccessSignInForm($form)
	{
		$values = $form->getValues();
		$this->user->setExpiration('14 days', FALSE);

		try
		{
			$this->user->login($values->username, $values->password);
			if ($this->backlink === 'editor')
			{
				$this->redirectUrl('/adminer/editor.php');
			}
			elseif ($this->backlink)
			{
				$this->restoreRequest($this->backlink);
			}
			$this->redirect('Homepage:');
		}
		catch (Nette\Security\AuthenticationException $e)
		{
			$form->addError($e->getMessage());
		}
	}

	public function actionOut()
	{
		$this->user->logout(TRUE);
		$this->flashSuccess($this->orm->quotes->getRandom(), 'Odhlášeno!');
		$this->redirect('in');
	}

}
