<?php
/**
 * Part of eng4tw project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Listener;

use Lyrasoft\Warder\Handler\UserHandler;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\User\UserSwitchService;
use Lyrasoft\Warder\Warder;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Core\Security\CsrfProtection;
use Windwalker\Core\User\Exception\AuthenticateFailException;
use Windwalker\Core\User\User;
use Windwalker\Core\View\HtmlView;
use Windwalker\Event\Event;
use function Windwalker\h;

/**
 * The UserListener class.
 *
 * @since  1.0
 */
class UserListener
{
    /**
     * Property package.
     *
     * @var  WarderPackage
     */
    protected $warder;

    /**
     * UserListener constructor.
     *
     * @param WarderPackage $warder
     */
    public function __construct(WarderPackage $warder = null)
    {
        $this->warder = $warder ?: WarderHelper::getPackage();
    }

    /**
     * onUserAfterLogin
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onUserAfterLogin(Event $event)
    {
        $options = $event['options'];

        $remember = $options['remember'];

        if ($remember) {
            $container = $this->warder->getContainer();

            $session = $container->get('session');

            $uri = $container->get('uri');

            setcookie(
                session_name(),
                $_COOKIE[session_name()],
                time() + 60 * 60 * 24 * 100,
                '/' . ltrim($session->getOption('cookie_path', $uri->path), '/'),
                $session->getOption('cookie_domain')
            );
        }
    }

    /**
     * onLoadAuthenticationMethods
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onLoadAuthenticationMethods(Event $event)
    {
        /** @var Authentication $auth */
        $auth = $event['auth'];

        $methods = $this->warder->get('methods', []);

        foreach ($methods as $name => $class) {
            if (!class_exists($class)) {
                throw new \LogicException('Class: ' . $class . ' not exists.');
            }

            if (!is_subclass_of($class, 'Windwalker\Authentication\Method\MethodInterface')) {
                throw new \LogicException(
                    'Class: ' . $class . ' must be sub class of Windwalker\Authentication\Method\MethodInterface'
                );
            }

            $auth->addMethod(
                $name,
                $this->warder->app->make($class, ['package' => $this->warder, 'warder' => $this->warder])
            );
        }
    }

    /**
     * onAfterInitialise
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterInitialise(Event $event)
    {
        $class = $this->warder->get('class.handler', UserHandler::class);

        User::setHandler($this->warder->app->make($class, ['package' => $this->warder]));
    }

    /**
     * onUserAuthorisation
     *
     * @param Event $event
     *
     * @return  void
     *
     * @throws  AuthenticateFailException
     */
    public function onUserAuthorisation(Event $event)
    {
        /** @var Credential $user */
        $user = $event['user'];

        $langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');

        if (property_exists($user, 'activation') && $user->activation) {
            throw new AuthenticateFailException(__($langPrefix . 'login.message.inactivated'), 40101);
        }

        if (property_exists($user, 'blocked') && $user->blocked) {
            throw new AuthenticateFailException(__($langPrefix . 'login.message.blocked'), 40102);
        }
    }

    /**
     * onViewBeforeRender
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onViewBeforeRender(Event $event)
    {
        if (!$event['view'] instanceof HtmlView) {
            return;
        }

        $data = $event['data'];

        if (!$data->user) {
            $data->user = User::get();
        }

        $userSwitcher = $this->warder->service(UserSwitchService::class);

        if ($userSwitcher->hasSwitched() && $this->warder->isAdmin()) {
            $user = Warder::getUser();
            $router = $this->warder->getCurrentPackage()->router;

            $msg = h(
                'span',
                ['class' => 'd-flex align-items-center'],
                [
                    h(
                        'span',
                        [],
                        __('warder.message.user.switch.switched.desc', $user->name)
                    ),
                    h(
                        'a',
                        [
                            'class' => 'btn btn-warning btn-sm ml-auto',
                            'href' => $router->to(
                                'users',
                                [
                                    '_method' => 'PATCH',
                                    'task' => 'switch',
                                    'action' => 'recover',
                                    CsrfProtection::getFormToken() => 1
                                ]
                            )
                        ],
                        __('warder.user.switch.recover.button')
                    )
                ]
            );

            $this->warder->app->addMessage((string) $msg, 'warning');
        }
    }
}
