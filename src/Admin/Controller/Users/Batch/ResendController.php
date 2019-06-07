<?php
/**
 * Part of emooc project.
 *
 * @copyright  Copyright (C) 2017 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Lyrasoft\Warder\User\ActivationService;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Batch\AbstractBatchController;
use Windwalker\Core\Language\Translator;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Router\CoreRouter;
use Windwalker\Core\User\User;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;
use Windwalker\DI\Annotation\Inject;

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
