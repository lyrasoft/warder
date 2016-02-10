<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Sentry\Controller\User\Forget;


use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\Authentication\User;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Crypt\Password;
use Windwalker\Data\Data;
use Windwalker\Sentry\Model\UserModel;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class ResetSaveController extends AbstractSaveController
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
	protected $langPrefix = 'user.forget.reset.';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->data['email']     = $this->input->getEmail('email');
		$this->data['token']     = $this->input->getString('token');
		$this->data['password']  = $this->input->getString('password');
		$this->data['password2'] = $this->input->getString('password2');
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return  bool
	 *
	 * @throws ValidFailException
	 */
	protected function doSave(Data $data)
	{
		if (!trim($this->data['password']))
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.entered'));
		}

		if ($this->data['password'] != $this->data['password2'])
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
		}

		$user = User::get(array('email' => $this->data['email']));

		if ($user->isNull())
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.user.not.found'));
		}

		$passwordObject = new Password;

		if (!$passwordObject->verify($this->data['token'], $user->reset_token))
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.invalid.token'));
		}

		$user->password    = $passwordObject->create($this->data['password']);
		$user->reset_token = '';
		$user->last_reset  = '';

		User::save($user);
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
		return $this->router->http('forget_reset', array('token' => $this->data['token'], 'email' => $this->data['email']));
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
		return $this->router->http('forget_complete');
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
