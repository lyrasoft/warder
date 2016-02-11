<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Windwalker\Warder\Controller\User\Forget;

use Phoenix\Controller\Display\ItemDisplayController;
use Windwalker\Warder\Model\UserModel;
use Windwalker\Warder\View\User\UserHtmlView;

/**
 * The GetController class.
 * 
 * @since  1.0
 */
class RequestGetController extends ItemDisplayController
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
	 * Property view.
	 *
	 * @var  UserHtmlView
	 */
	protected $view;
}
