<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User;

use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Model\UserModel;
use Phoenix\Controller\AbstractSaveController;
use Windwalker\Data\DataInterface;

/**
 * The SaveController class.
 *
 * @since  1.0
 */
class LoginSaveController extends AbstractSaveController
{
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
	 * @param DataInterface $data
	 *
	 * @return void
	 */
	protected function doSave(DataInterface $data)
	{
		$options   = [];
		$provider  = $this->input->get('provider');
		$loginName = WarderHelper::getLoginName();

		if ($provider)
		{
			$options['provider'] = strtolower($provider);

			$data->$loginName = null;
			$data->password = null;
		}

		$this->model->login($data->$loginName, $data->password, $data->remember, $options);
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
		$return = $this->getUserState($this->getContext('return'));

		if ($return)
		{
			$this->removeUserState($this->getContext('return'));

			return base64_decode($return);
		}
		else
		{
			return $this->router->route(WarderHelper::getPackage()->get('frontend.redirect.login', 'home'));
		}
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
}
