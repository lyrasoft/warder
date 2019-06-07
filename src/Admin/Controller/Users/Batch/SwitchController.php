<?php
/**
 * Part of warder project.
 *
 * @copyright  Copyright (C) 2019 ${ORGANIZATION}.
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Lyrasoft\Warder\User\UserSwitchService;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\DI\Annotation\Inject;

/**
 * The SwitchController class.
 *
 * @since  __DEPLOY_VERSION__
 */
class SwitchController extends AbstractController
{
    /**
     * Property userSwitcher.
     *
     * @Inject()
     *
     * @var UserSwitchService
     */
    protected $userSwitcher;

    /**
     * The main execution process.
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     * @throws \ReflectionException
     * @throws \Windwalker\DI\Exception\DependencyResolutionException
     */
    protected function doExecute()
    {
        $this->delegate($this->app->input->get('action', 'change'));

        $return = $this->input->server->getRaw('HTTP_REFERER')
            ?: $this->router->route('users');

        $this->setRedirect($return);
    }

    /**
     * change
     *
     * @return  void
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function change(): void
    {
        $ids = $this->input->getArray('id');

        if ($ids === []) {
            throw new ValidateFailException('No user ID');
        }

        // Only get first
        $id = array_shift($ids);

        $targetUser = Warder::getUser($id);

        $this->userSwitcher->switch($targetUser);

        $this->addMessage(__('warder.message.user.switch.success', $targetUser->name), Bootstrap::MSG_SUCCESS);
    }

    /**
     * recover
     *
     * @return  void
     *
     * @since  __DEPLOY_VERSION__
     */
    protected function recover(): void
    {
        $this->userSwitcher->recover();

        $this->addMessage(__('warder.message.user.recover.success'), Bootstrap::MSG_SUCCESS);
    }
}
