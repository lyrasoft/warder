<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Phoenix\Controller\AbstractSaveController;
use Windwalker\Data\Data;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Model\UserModel;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class LoginSaveController extends AbstractSaveController
{
	/**
	 * Property name.
	 *
	 * @var  string
	 */
	protected $name = 'user';

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
	protected $langPrefix = 'warder.login.';

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		if (UserHelper::isLogin())
		{
			$this->redirect($this->getSuccessRedirect());

			return;
		}

		parent::prepareExecute();
	}

	/**
	 * doSave
	 *
	 * @param Data $data
	 *
	 * @return void
	 */
	protected function doSave(Data $data)
	{
		$loginName = WarderHelper::getLoginName();

		$this->model->login($data->$loginName, $data->password, $data->remember, []);
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
		$return = $this->getUserState($this->getContext('return'));

		if ($return)
		{
			$this->removeUserState($this->getContext('return'));

			return base64_decode($return);
		}
		else
		{
			return $this->router->route(WarderHelper::getPackage()->get('admin.redirect.login', 'home'), $this->getRedirectQuery());
		}
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
		return $this->router->route('login', $this->getRedirectQuery());
	}
}
