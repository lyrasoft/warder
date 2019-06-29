<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Controller\User\Ajax;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Controller\AbstractController;

/**
 * The CheckAccountController class.
 *
 * @since  __DEPLOY_VERSION__
 */
class CheckAccountController extends AbstractController
{
    /**
     * The main execution process.
     *
     * @return  mixed
     */
    protected function doExecute()
    {
        $account = $this->input->getString('account');

        $loginName = WarderHelper::getLoginName();

        $user = Warder::getUser([$loginName => $account]);

        return ['exists' => $user->notNull()];
    }
}
