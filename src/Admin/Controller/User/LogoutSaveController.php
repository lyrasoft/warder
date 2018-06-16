<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Phoenix\Controller\AbstractPhoenixController;
use Windwalker\Core\User\User;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class LogoutSaveController extends AbstractPhoenixController
{
    /**
     * doExecute
     *
     * @return  mixed
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function doExecute()
    {
        User::logout();

        $return = $this->input->getBase64(
            $this->package->get('admin.login.return_key', 'return')
        );

        if ($return) {
            $this->setRedirect(base64_decode($return));

            return true;
        }

        $this->setRedirect($this->router->route(WarderHelper::getPackage()->get('admin.redirect.logout', 'home')));

        return true;
    }
}
