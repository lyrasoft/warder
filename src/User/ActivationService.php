<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\User;

use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Warder;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Core\Mailer\Mailer;
use Windwalker\Core\Mailer\MailMessage;
use Windwalker\Core\Package\PackageHelper;
use Windwalker\Core\Renderer\RendererHelper;
use Windwalker\Core\Router\CoreRouter;

/**
 * The ActivationService class.
 *
 * @since  1.7
 */
class ActivationService
{
    public const RE_ACTIVATE_SESSION_KEY = 'reactivate.mail';

    /**
     * Property warder.
     *
     * @var  WarderPackage
     */
    protected $warder;

    /**
     * ActivationService constructor.
     */
    public function __construct()
    {
        $this->warder = WarderHelper::getPackage();
    }

    /**
     * resendUserActivateMail
     *
     * @param array $conditions
     *
     * @return  void
     *
     * @throws \ReflectionException
     *
     * @since  1.7
     */
    public function sendActivateMail($conditions = []): void
    {
        $user = Warder::getUser($conditions);

        $user->token = Warder::getToken($user['email']);

        $user->activation = Warder::hashPassword($user->token);

        Warder::save($user);

        RendererHelper::addGlobalPath(WarderPackage::dir() . '/Templates/user');

        Mailer::send(function (MailMessage $message) use ($user) {
            $router = PackageHelper::getPackage(WarderHelper::getFrontendPackage(true))->router;

            $link = $router->to(
                'registration_activate',
                ['email' => $user->email, 'token' => $user->token]
            )->full();

            $message->subject(__('warder.registration.mail.subject'))
                ->to($user->email)
                ->renderBody(
                    'mail.registration',
                    compact('user', 'link'),
                    'edge',
                    'front',
                    'user'
                );
        });
    }
}
