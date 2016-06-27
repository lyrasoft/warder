<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Registration;

use Phoenix\Controller\AbstractSaveController;
use Phoenix\Form\FieldDefinitionResolver;
use Phoenix\Mail\SwiftMailer;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Router\Router;
use Windwalker\Core\View\AbstractView;
use Windwalker\Core\View\BladeHtmlView;
use Windwalker\Core\View\PhpHtmlView;
use Windwalker\Data\Data;
use Windwalker\Validator\Rule\EmailValidator;
use Lyrasoft\Warder\Form\User\RegistrationDefinition;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Model\UserModel;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class RegistrationSaveController extends AbstractSaveController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'user';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = 'user';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = 'user';

	/**
	 * Property model.
	 *
	 * @var  UserModel
	 */
	protected $model;

	/**
	 * Property formControl.
	 *
	 * @var  string
	 */
	protected $formControl = 'user';

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.registration.';

	/**
	 * Property useTransaction.
	 *
	 * @var  bool
	 */
	protected $useTransaction = true;

	/**
	 * Property token.
	 *
	 * @var  string
	 */
	protected $token;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		if (UserHelper::isLogin())
		{
			$warder = WarderHelper::getPackage();

			$this->redirect($this->router->route($warder->get('frontend.redirect.login', 'home')));

			return;
		}

		parent::prepareExecute();

		$this->token = UserHelper::getToken($this->data['email']);

		$this->data['activation'] = UserHelper::hashPassword($this->token);
	}

	/**
	 * preSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 */
	protected function preSave(Data $data)
	{
		unset($this->data['password']);
		unset($this->data['password2']);
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function doSave(Data $data)
	{
		$this->filter($data);

		$this->validate($data);

		$this->model->register($data);
	}

	/**
	 * postSave
	 *
	 * @param Data $user
	 *
	 * @return  void
	 */
	protected function postSave(Data $user)
	{
		// Mail
		$view = $this->getView();

		$view['link'] = $this->router->route('registration_activate', ['email' => $user->email, 'token' => $this->token], Router::TYPE_FULL);
		$view['user'] = $user;

		$body = $this->getMailBody($view);

		$this->sendEmail($user->email, $body);
	}

	/**
	 * getMailBody
	 *
	 * @param PhpHtmlView $view
	 *
	 * @return  string
	 */
	protected function getMailBody(PhpHtmlView $view)
	{
		return $view->setLayout('mail.registration')->render();
	}

	/**
	 * sendEmail
	 *
	 * @param string $email
	 * @param string $body
	 *
	 * @return  void
	 */
	protected function sendEmail($email, $body)
	{
		$message = SwiftMailer::newMessage(Translator::translate($this->langPrefix . 'mail.subject'))
			->addFrom($this->app->get('mail.from.email', $this->app->get('mail.from.email')), $this->app->get('mail.from.name', $this->app->get('mail.from.name')))
			->addTo($email)
			->setBody($body);

		SwiftMailer::send($message);
	}

	/**
	 * validate
	 *
	 * @param  Data $data
	 *
	 * @return  void
	 *
	 * @throws ValidFailException
	 */
	protected function validate(Data $data)
	{
		$validator = new EmailValidator;

		if (!$validator->validate($data->email))
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.email.invalid'));
		}

		$form = $this->model->getForm('registration', 'user');

		$this->model->validate($data->dump(), $form);

		if (!$data->password)
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.entered'));
		}

		if ($data->password != $data->password2)
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
		}

		unset($data->password2);
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param Data $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(Data $data = null)
	{
		return $this->router->route('login');
	}

	/**
	 * getFailRedirect
	 *
	 * @param Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data = null)
	{
		return $this->router->route('registration');
	}
}
