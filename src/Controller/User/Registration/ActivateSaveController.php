<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Controller\User\Registration;

use Phoenix\Controller\AbstractSaveController;
use Windwalker\Core\Authentication\User;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Model\Exception\ValidFailException;
use Windwalker\Data\Data;
use Windwalker\Warder\Helper\UserHelper;
use Windwalker\Warder\Helper\WarderHelper;
use Windwalker\Warder\Model\UserModel;

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

			$this->redirect($this->router->http($warder->get('frontend.redirect.login', 'home')));

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
	 * @throws ValidFailException
	 */
	protected function doSave(Data $data)
	{
		$user = User::get(['email' => $this->data['email']]);

		if (!UserHelper::verifyPassword($this->data['token'], $user->activation))
		{
			throw new ValidFailException(Translator::translate($this->langPrefix . 'message.activate.fail'));
		}

		$user->activation = '';
		$user->blocked = 0;

		User::save($user);

		return true;
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
		return $this->router->http('login');
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