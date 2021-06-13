<?php
/**
 * Part of emooc project.
 *
 * @copyright  Copyright (C) 2017 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Lyrasoft\Warder\User\ActivationService;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Batch\AbstractBatchController;
use Windwalker\Legacy\Core\Language\Translator;
use Windwalker\Legacy\Core\Mailer\MailMessage;
use Windwalker\Legacy\Core\Router\CoreRouter;
use Windwalker\Legacy\Core\User\User;
use Windwalker\Legacy\Core\User\UserDataInterface;
use Windwalker\Legacy\Data\Data;
use Windwalker\Legacy\Data\DataInterface;
use Windwalker\Legacy\DI\Annotation\Inject;

/**
 * The ResendController class.
 *
 * @since  1.7
 */
class ResendController extends AbstractBatchController
{
    /**
     * Property action.
     *
     * @var  string
     */
    protected $action = 'resend';

    /**
     * Property data.
     *
     * @var  array
     */
    protected $data = [
        'activation' => '__placeholder__'
    ];

    /**
     * Property langPrefix.
     *
     * @var  string
     */
    protected $langPrefix = 'warder.';

    /**
     * Property activateService.
     *
     * @Inject()
     *
     * @var ActivationService
     */
    protected $activateService;

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \ReflectionException
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();

        Translator::loadFile('mail', 'ini', 'front');
    }

    /**
     * save
     *
     * @param string|int    $pk
     * @param DataInterface $data
     *
     * @return  DataInterface
     * @throws \ReflectionException
     */
    protected function save($pk, DataInterface $data)
    {
        $user = Warder::getUser($pk);

        $this->activateService->sendActivateMail($pk);

        return $user;
    }
}
