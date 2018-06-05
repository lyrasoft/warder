<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Registration;

use Lyrasoft\Warder\Helper\UserHelper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Model\UserRepository;
use Lyrasoft\Warder\View\User\UserHtmlView;
use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Core\Model\ModelRepository;
use Windwalker\Core\View\AbstractView;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class RegistrationGetController extends EditDisplayController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'User';

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
        if (UserHelper::isLogin()) {
            $warder = WarderHelper::getPackage();

            $this->redirect($this->router->route($warder->get('frontend.redirect.login', 'home')));

            return;
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

        // Force registration do not get any item
        $model['item.pk'] = -1;
    }
}
