<?php

namespace App;

use Nette;
use Clevis\Skeleton\BasePresenter;


class SignPresenter extends BasePresenter
{

	public function createComponentSignInForm($name)
	{
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
		$this->getUser()->setExpiration('14 days', FALSE);

		try
		{
			$this->getUser()->login($values->username, $values->password);
			$this->flashMessage('You have been signed in.');
			$this->redirect('Homepage:');
		}
		catch (Nette\Security\AuthenticationException $e)
		{
			$form->addError($e->getMessage());
		}
	}

	public function actionOut()
	{
		$this->getUser()->logout();
		$this->flashMessage('You have been signed out.');
		$this->redirect('in');
	}

}
