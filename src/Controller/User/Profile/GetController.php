<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Profile;

use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Model\ProfileModel;
use Lyrasoft\Warder\View\Profile\ProfileHtmlView;
use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class GetController extends EditDisplayController
{
	/**
	 * Property model.
	 *
	 * @var  ProfileModel
	 */
	protected $model = 'Profile';

	/**
	 * Property view.
	 *
	 * @var  ProfileHtmlView
	 */
	protected $view = 'Profile';

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
	 * Prepare view and default model.
	 *
	 * You can configure default model state here, or add more sub models to view.
	 * Remember to call parent to make sure default model already set in view.
	 *
	 * @param AbstractView    $view  The view to render page.
	 * @param ModelRepository $model The default mode.
	 *
	 * @return  void
	 */
	protected function prepareViewModel(AbstractView $view, ModelRepository $model)
	{
		parent::prepareViewModel($view, $model);
	}
}
