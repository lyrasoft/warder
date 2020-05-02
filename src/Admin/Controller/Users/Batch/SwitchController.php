<?php
/**
 * Part of warder project.
 *
 * @copyright  Copyright (C) 2019 LYRASOFT.
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Admin\Controller\Users\Batch;

use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\User\UserSwitchService;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Controller\AbstractController;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\DI\Annotation\Inject;
use Windwalker\Utilities\Arr;

/**
 * The SwitchController class.
 *
 * @since  1.7
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
     * @var string[]
     */
    protected $defaultOptions = [
        'group_field' => 'group'
    ];

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
     * @since  1.7
     */
    protected function change(): void
    {
        $ids = $this->input->getArray('id');
        $options = Arr::mergeRecursive(
            $this->defaultOptions,
            [
                'keepgroup' => $this->input->post->get('keepgroup'),
            ]
        );

        if ($ids === []) {
            throw new ValidateFailException('No user ID');
        }

        // Only get first
        $id = array_shift($ids);

        $targetUser = Warder::getUser($id);

        $warder = WarderHelper::getPackage();

        if ($warder->get('session_separate.enabled')) {
            $this->userSwitcher->frontendLogin($targetUser, $options);
        } else {
            $this->userSwitcher->switch($targetUser, $options);
        }

        $this->addMessage(__('warder.message.user.switch.success', $targetUser->name), Bootstrap::MSG_SUCCESS);
    }

    /**
     * recover
     *
     * @return  void
     *
     * @since  1.7
     */
    protected function recover(): void
    {
        $this->userSwitcher->recover();

        $this->addMessage(__('warder.message.user.recover.success'), Bootstrap::MSG_SUCCESS);
    }
}
