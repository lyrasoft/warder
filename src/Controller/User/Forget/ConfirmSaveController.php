<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Controller\User\Forget;

use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\User\User;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Crypt\Password;
use Windwalker\Data\Data;
use Windwalker\Filter\InputFilter;
use Windwalker\Warder\Model\UserModel;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class ConfirmSaveController extends AbstractSaveController
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
	protected $langPrefix = 'warder.forget.confirm.';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		$this->data['token'] = $this->input->get('token');
		$this->data['email'] = $this->input->getEmail('email');
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
		$user = User::get(array('email' => $this->data['email']));

		if ($user->isNull())
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'user.not.found'));
		}

		// Check token
		$password = new Password;

		if (!$password->verify($this->data['token'], $user->reset_token))
		{
			throw new ValidFailException('Invalid Token');
		}

		return true;
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
		return $this->router->route('forget_confirm', array('email' => $this->data['email']));
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
		return $this->router->route('forget_reset', array('token' => $this->data['token'], 'email' => $this->data['email']));
	}
}
