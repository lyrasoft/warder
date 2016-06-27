<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Controller\User\Profile;

use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\User\User;
use Windwalker\Core\User\UserData;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;
use Windwalker\Record\Record;
use Windwalker\Validator\Rule\EmailValidator;
use Windwalker\Warder\Helper\UserHelper;
use Windwalker\Warder\Helper\WarderHelper;
use Windwalker\Warder\Model\UserModel;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class SaveController extends AbstractSaveController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'profile';

	/**
	 * Property itemName.
	 *
	 * @var  string
	 */
	protected $itemName = 'profile';

	/**
	 * Property listName.
	 *
	 * @var  string
	 */
	protected $listName = 'profile';

	/**
	 * Property model.
	 *
	 * @var  UserModel
	 */
	protected $model;

	/**
	 * Property useTransaction.
	 *
	 * @var  bool
	 */
	protected $useTransaction = false;

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
	protected $langPrefix = 'warder.profile.';

	/**
	 * Property user.
	 *
	 * @var  UserData
	 */
	protected $user;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		if (!UserHelper::isLogin())
		{
			UserHelper::goToLogin();
		}

		parent::prepareExecute();
	}

	/**
	 * preSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function preSave(Data $data)
	{
		$this->user = User::get();

		$data->id = $this->user->id;

		// Remove password so that session will not store this data
		unset($this->data['password']);
		unset($this->data['password2']);
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

		// Set user data to session if is current user.
		if (User::get()->id == $data->id)
		{
			User::makeUserLogin(User::get($data->id));
		}
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

		parent::validate($data);

		$loginName = WarderHelper::getLoginName();

		if ($loginName != 'email')
		{
			$user = User::get(array($loginName => $data->$loginName));

			if ($user->notNull() && $user->id != $data->id)
			{
				throw new ValidFailException(Translator::translate($this->langPrefix . 'message.user.account.exists'));
			}
		}

		$user = User::get(array('email' => $data->email));

		if ($user->notNull() && $user->id != $data->id)
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.user.email.exists'));
		}

		if ('' !== (string) $data->password)
		{
			if ($data->password != $data->password2)
			{
				throw new ValidFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
			}

			unset($data->password2);
		}
		else
		{
			unset($data->password);
		}
	}

	/**
	 * getFailRedirect
	 *
	 * @param  Data $data
	 *
	 * @return  string
	 */
	protected function getFailRedirect(Data $data = null)
	{
		unset($data->id);

		return parent::getFailRedirect($data);
	}

	/**
	 * getModel
	 *
	 * @param string $name
	 * @param bool   $forceNew
	 *
	 * @return  mixed
	 */
	public function getModel($name = 'User', $forceNew = false)
	{
		return parent::getModel($name, $forceNew);
	}

	/**
	 * getRecord
	 *
	 * @param string $name
	 *
	 * @return  Record
	 */
	public function getRecord($name = 'User')
	{
		return parent::getRecord($name);
	}
}
