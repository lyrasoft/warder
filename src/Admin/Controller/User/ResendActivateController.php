<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Admin\Controller\User;

use Lyrasoft\Warder\User\ActivationService;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Controller\AbstractController;
use Windwalker\DI\Annotation\Inject;

/**
 * The ResendActivateController class.
 *
 * @since  __DEPLOY_VERSION__
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
