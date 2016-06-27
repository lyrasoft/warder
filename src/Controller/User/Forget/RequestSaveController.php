<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Phoenix\Controller\AbstractSaveController;
use Phoenix\Mail\SwiftMailer;
use Windwalker\Core\User\User;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Core\Router\Router;
use Windwalker\Core\View\PhpHtmlView;
use Windwalker\Crypt\Password;
use Windwalker\Data\Data;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Model\UserModel;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class RequestSaveController extends AbstractSaveController
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
	 * Property useTransaction.
	 *
	 * @var  bool
	 */
	protected $useTransaction = false;

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.forget.request.';

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return  bool
	 *
	 * @throws ValidFailException
	 * @throws \Exception
	 */
	protected function doSave(Data $data)
	{
		$email = $this->input->getEmail('email');

		if (!$email)
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
		}

		$view = $this->getView();

		$user = User::get(array('email' => $email));

		if ($user->isNull())
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
		}

		$token = UserHelper::getToken($user->email);
		$link  = $this->router->route('forget_confirm', array('token' => $token, 'email' => $email), Router::TYPE_FULL);

		$password = new Password;

		$user->reset_token = $password->create($token);
		$user->last_reset = DateTime::create()->toSql();

		User::save($user);

		$view['user']  = $user;
		$view['token'] = $token;
		$view['link']  = $link;

		$body = $this->getMailBody($view);

		$this->sendEmail($user->email, $body);

		return true;
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
		return $view->setLayout('mail.forget')->render();
	}

	/**
	 * sendEmail
	 *
	 * @param string  $email
	 * @param string  $body
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
	 * getFailRedirect
	 *
	 * @param Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data = null)
	{
		return $this->router->route('forget_request');
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
		return $this->router->route('forget_confirm');
	}

	/**
	 * postSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function postSave(Data $data)
	{
		parent::postSave($data);
	}

	/**
	 * getRecord
	 *
	 * @param string $name
	 *
	 * @return  \Windwalker\Record\Record
	 */
	public function getRecord($name = 'User')
	{
		return parent::getRecord($name);
	}
}
