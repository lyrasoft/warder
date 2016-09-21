<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Profile;

use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Core\Model\ModelRepository;
use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Model\UserModel;
use Lyrasoft\Warder\View\User\UserHtmlView;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class GetController extends EditDisplayController
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
	 * Property view.
	 *
	 * @var  UserHtmlView
	 */
	protected $view;

	/**
	 * prepareExecute
	 *
	 * @return  void
	 */
	protected function prepareExecute()
	{
		if (!UserHelper::isLogin())
		{
			UserHelper::goToLogin($this->app->uri->full);
		}

		parent::prepareExecute();
	}

	/**
	 * prepareExecute
	 *
	 * @param ModelRepository $model
	 *
	 * @return void
	 */
	protected function prepareModelState(ModelRepository $model)
	{
		parent::prepareModelState($model);

		// Only use once
		$this->removeUserState($this->getContext('edit.data'));
	}
}
