<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Controller\User\Registration;

use Phoenix\Controller\AbstractSaveController;
use Phoenix\Mail\SwiftMailer;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Router\Router;
use Windwalker\Data\Data;
use Windwalker\Sentry\Form\User\RegistrationDefinition;
use Windwalker\Sentry\Helper\UserHelper;
use Windwalker\Sentry\Model\UserModel;

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
	protected $langPrefix = 'user.registration.';

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

		$view['link'] = $this->router->http('registration_activate', ['email' => $user->email, 'token' => $this->token], Router::TYPE_FULL);
		$view['user'] = $user;

		$body = $view->setLayout('mail.registration')->render();

		$message = SwiftMailer::newMessage(Translator::translate($this->langPrefix . 'mail.subject'))
			->addFrom($this->app->get('mail.from', $this->app->get('mail.from')))
			->addTo($user->email)
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
		$form = $this->model->getForm(new RegistrationDefinition, 'user');

		$this->model->validate($data->dump(), $form);

		if ($data->password != $data->password2)
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
		}

		unset($data->password2);

//		if (!$data->tos)
//		{
//			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.please.accept.tos'));
//		}
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
		return $this->router->http('login');
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
		return $this->router->http('registration');
	}
}
