<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Registration;

use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\User\User;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidateFailException;
use Windwalker\Data\Data;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Model\UserModel;
use Windwalker\Data\DataInterface;

/**
 * The SaveController class.
 * 
 * @since  1.0
 */
class ActivateSaveController extends AbstractSaveController
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
	 * Property langPrefix.
	 *
	 * @var  string
	 */
	protected $langPrefix = 'warder.activate.';

	/**
	 * Property useTransaction.
	 *
	 * @var  bool
	 */
	protected $useTransaction = true;

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

		$this->data['email'] = $this->input->getEmail('email');
		$this->data['token'] = $this->input->get('token');
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return bool
	 *
	 * @throws ValidateFailException
	 */
	protected function doSave(Data $data)
	{
		$user = User::get(['email' => $this->data['email']]);

		if (!UserHelper::verifyPassword($this->data['token'], $user->activation))
		{
			throw new ValidateFailException(Translator::translate($this->langPrefix . 'message.activate.fail'));
		}

		$user->activation = '';
		$user->blocked = 0;

		User::save($user);

		return true;
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
		return $this->router->route('login');
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
		return $this->router->route('login');
	}

	/**
	 * checkToken
	 *
	 * @return  bool
	 */
	protected function checkToken()
	{
		return true;
	}
}
