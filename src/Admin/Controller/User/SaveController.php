<?php
/**
 * Part of phoenix project. 
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Lyrasoft\Luna\Field\Image\SingleImageDragField;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\User\User;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Crypt\Password;
use Windwalker\Data\Data;
use Lyrasoft\Warder\Helper\AvatarUploadHelper;
use Lyrasoft\Warder\Helper\WarderHelper;

/**
 * The SaveController class.
 * 
 * @since  {DEPLOY_VERSION}
 */
class SaveController extends AbstractSaveController
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
	protected $listName = 'users';

	/**
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.';

	/**
	 * preSave
	 *
	 * @param Data $data
	 *
	 * @return  void
	 */
	protected function preSave(Data $data)
	{
		// Remove password from session
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
		// Image
		if (false !== SingleImageDragField::uploadFromController($this, 'avatar', $data, AvatarUploadHelper::getPath($data->id)))
		{
			$this->model->save($data);
		}

		// Set user data to session if is current user.
		if (User::get()->id == $data->id)
		{
			User::makeUserLoggedIn(User::get($data->id));
		}
	}

	/**
	 * validate
	 *
	 * @param Data $data
	 *
	 * @return  void
	 *
	 * @throws ValidateFailException
	 */
	protected function validate(Data $data)
	{
		parent::validate($data);

		$loginName = WarderHelper::getLoginName();

		if ($loginName != 'email')
		{
			$user = User::get(array($loginName => $data->$loginName));

			if ($user->notNull() && $user->id != $data->id)
			{
				throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.user.account.exists'));
			}
		}

		$user = User::get(array('email' => $data->email));

		if ($user->notNull() && $user->id != $data->id)
		{
			throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.user.email.exists'));
		}

		if ('' !== (string) $data->password)
		{
			if ($data->password != $data->password2)
			{
				throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.password.not.match'));
			}

			unset($data->password2);
		}
		else
		{
			unset($data->password);
		}
	}
}
