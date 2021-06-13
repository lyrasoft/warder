<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Display\DisplayController;
use Windwalker\Legacy\Router\Exception\RouteNotFoundException;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class LoginGetController extends DisplayController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'user';

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function prepareExecute()
    {
        $warder = WarderHelper::getPackage();

        if (!$warder->get('frontend.allow_login', true)) {
            throw new RouteNotFoundException('Not found');
        }

        $return = $this->input->getBase64(
            $this->package->get('frontend.login.return_key', 'return')
        );

        if (Warder::isLogin()) {
            if ($return) {
                $this->app->redirect(base64_decode($return));
            } else {
                $this->app->redirect($this->getHomeRedirect());
            }

            return;
        }

        if ($return) {
            $this->setUserState($this->getContext('return'), $return);
        }

        parent::prepareExecute();
    }

    /**
     * getHomeRedirect
     *
     * @return  string
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function getHomeRedirect()
    {
        return $this->router->route(WarderHelper::getPackage()->get('frontend.redirect.login', 'home'));
    }
}
