<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Lyrasoft\Warder\User\ActivationService;
use Windwalker\Legacy\Core\Controller\AbstractController;
use Windwalker\Legacy\DI\Annotation\Inject;

/**
 * The ResendActivateController class.
 *
 * @since  1.7
 */
class ResendActivateController extends AbstractController
{
    /**
     * Property activateService.
     *
     * @Inject()
     *
     * @var ActivationService
     */
    protected $activateService;

    /**
     * The main execution process.
     *
     * @return  mixed
     * @throws \ReflectionException
     */
    protected function doExecute()
    {
        $email = $this->input->getEmail('email');

        if (!$email) {
            throw new \RuntimeException('No email');
        }

        $this->activateService->sendActivateMail(['email' => $email]);

        $this->addMessage(__('warder.message.resend.activate.mail.success'));

        $this->setRedirect($this->router->to('login'));

        return true;
    }
}
