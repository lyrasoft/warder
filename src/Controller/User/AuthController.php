<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User;

use Phoenix\Controller\AbstractPhoenixController;

/**
 * The AuthController class.
 *
 * @since  1.0
 */
class AuthController extends AbstractPhoenixController
{
    /**
     * doExecute
     *
     * @return  mixed
     */
    protected function doExecute()
    {
        \Hybrid_Endpoint::process();
    }
}
