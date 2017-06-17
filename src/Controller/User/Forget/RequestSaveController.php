<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Model\UserModel;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\DateTime\DateTime;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Mailer\Mailer;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Core\User\User;
use Windwalker\Core\View\HtmlView;
use Windwalker\Crypt\Password;
use Windwalker\Data\DataInterface;

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
	 * @param DataInterface $data
	 *
	 * @return  bool
	 *
	 * @throws ValidateFailException
	 * @throws \Exception
	 */
	protected function doSave(DataInterface $data)
	{
		$email = $this->input->getEmail('email');

		if (!$email)
		{
			throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
		}

		$view = $this->getView();

		$user = User::get(['email' => $email]);

		if ($user->isNull())
		{
			throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
		}

		$token = UserHelper::getToken($user->email);
		$link  = $this->router->route('forget_confirm', ['token' => $token, 'email' => $email], CoreRouter::TYPE_FULL);

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
	 * @param HtmlView $view
	 *
	 * @return  string
	 */
	protected function getMailBody(HtmlView $view)
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
		Mailer::send(function (MailMessage $message) use ($email, $body)
		{
			$message->subject(Translator::translate($this->langPrefix . 'mail.subject'))
				->from($this->app->get('mail.from.email', $this->app->get('mail.from.email')), $this->app->get('mail.from.name', $this->app->get('mail.from.name')))
				->to($email)
				->body($body);
		});
	}

	/**
	 * getFailRedirect
	 *
	 * @param DataInterface $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(DataInterface $data = null)
	{
		return $this->router->route('forget_request');
	}

	/**
	 * getSuccessRedirect
	 *
	 * @param DataInterface $data
	 *
	 * @return  string
	 */
	protected function getSuccessRedirect(DataInterface $data = null)
	{
		return $this->router->route('forget_confirm');
	}

	/**
	 * postSave
	 *
	 * @param DataInterface $data
	 *
	 * @return  void
	 */
	protected function postSave(DataInterface $data)
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
