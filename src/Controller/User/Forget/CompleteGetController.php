<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\View\User\UserHtmlView;
use Phoenix\Controller\Display\ItemDisplayController;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class CompleteGetController extends ItemDisplayController
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
     * @var  UserRepository
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
        parent::prepareExecute();
    }
}
