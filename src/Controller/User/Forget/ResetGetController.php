<?php
/**
 * Part of Front project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Controller\User\Forget;

use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\View\User\UserHtmlView;
use Lyrasoft\Warder\Warder;
use Phoenix\Controller\Display\ItemDisplayController;
use Windwalker\Core\Frontend\Bootstrap;
use Windwalker\Core\User\User;

/**
 * The GetController class.
 *
 * @since  1.0
 */
class ResetGetController extends ItemDisplayController
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'user';

    /**
     * Property itemName.
     *
     * @var  string
     */
    protected $itemName = 'user';

    /**
     * Property listName.
     *
     * @var  string
     */
    protected $listName = 'user';

    /**
     * Property model.
     *
     * @var  UserRepository
     */
    protected $model;

    /**
     * Property view.
     *
     * @var  UserHtmlView
     */
    protected $view;

    /**
     * prepareExecute
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function prepareExecute()
    {
        parent::prepareExecute();

        $this->view['email'] = $this->input->getEmail('email');
        $this->view['token'] = $this->input->get('token');

        // Check email and token
        $user = User::get(['email' => $this->view['email']]);

        if ($user->isNull()) {
            $this->backToConfirm(__($this->langPrefix . 'user.not.found'));

            return;
        }

        if (!Warder::verifyPassword($this->view['token'], $user->reset_token)) {
            $this->backToConfirm('Invalid Token');

            return;
        }
    }

    /**
     * backToConfirm
     *
     * @param string $message
     * @param string $type
     *
     * @return  void
     * @throws \Psr\Cache\InvalidArgumentException
     */
    protected function backToConfirm($message = null, $type = Bootstrap::MSG_WARNING)
    {
        $this->redirect(
            $this->router->route('forget_confirm', ['token' => $this->view['token'], 'email' => $this->view['email']]),
            $message,
            $type
        );
    }
}
