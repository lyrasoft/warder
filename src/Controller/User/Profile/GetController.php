<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Profile;

use Lyrasoft\Warder\Repository\ProfileRepository;
use Lyrasoft\Warder\View\Profile\ProfileHtmlView;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Display\EditDisplayController;
use Windwalker\Legacy\Core\Repository\Repository;
use Windwalker\Legacy\Core\View\AbstractView;

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
     * @var  ProfileRepository
     */
    protected $repository = 'Profile';

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
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function prepareExecute()
    {
        if (!Warder::isLogin()) {
            Warder::goToLogin($this->app->uri->full);
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
    }
}
