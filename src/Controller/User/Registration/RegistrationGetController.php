<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Registration;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\View\User\UserHtmlView;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Legacy\Core\Repository\Repository;
use Windwalker\Legacy\Core\View\AbstractView;
use Windwalker\Legacy\Router\Exception\RouteNotFoundException;

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
    protected $repository;

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function prepareExecute()
    {
        $warder = WarderHelper::getPackage();

        if (!$warder->get('allow_registration', true)) {
            throw new RouteNotFoundException('Not found');
        }

        if (Warder::isLogin()) {
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
     * @param Repository $repository The default mode.
     *
     * @return  void
     * @throws \ReflectionException
     */
    protected function prepareViewModel(AbstractView $view, Repository $repository)
    {
        parent::prepareViewModel($view, $repository);

        // Force registration do not get any item
        $repository['item.pk'] = -1;
    }
}
