<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    __LICENSE__
 */

namespace Lyrasoft\Warder\User;

use Lyrasoft\Warder\Data\WarderUserDataInterface;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\User\User;
use Windwalker\Session\Session;

/**
 * The UserSwitchService class.
 *
 * @since  __DEPLOY_VERSION__
 */
class UserSwitchService
{
    public const ORIGIN_USER_SESSION_KEY = 'origin_user';

    /**
     * Property session.
     *
     * @var  Session
     */
    protected $session;

    /**
     * UserSwitchService constructor.
     *
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * getOriginUser
     *
     * @return  WarderUserDataInterface|null
     *
     * @since  __DEPLOY_VERSION__
     */
    public function getOriginUser(): ?WarderUserDataInterface
    {
        return $this->session->get(static::ORIGIN_USER_SESSION_KEY);
    }

    /**
     * setOriginUser
     *
     * @param WarderUserDataInterface $user
     *
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function setOriginUser(WarderUserDataInterface $user): self
    {
        $this->session->set(static::ORIGIN_USER_SESSION_KEY, $user);

        return $this;
    }

    /**
     * hasSwitched
     *
     * @return  bool
     *
     * @since  __DEPLOY_VERSION__
     */
    public function hasSwitched(): bool
    {
        return $this->session->exists(static::ORIGIN_USER_SESSION_KEY);
    }

    /**
     * removeOriginUser
     *
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function removeOriginUser(): self
    {
        $this->session->remove(static::ORIGIN_USER_SESSION_KEY);

        return $this;
    }

    /**
     * switch
     *
     * @param WarderUserDataInterface $targetUser
     *
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function switch(WarderUserDataInterface $targetUser): self
    {
        $user = $this->getOriginUser() ?: Warder::getUser();

        unset($targetUser->password);

        $targetUser->group = $user['group'];

        $this->setOriginUser($user);

        User::makeUserLoggedIn($targetUser);

        return $this;
    }

    /**
     * recover
     *
     * @return  static
     *
     * @since  __DEPLOY_VERSION__
     */
    public function recover(): self
    {
        $user = $this->getOriginUser();

        if (!$user) {
            throw new ValidateFailException('No origin user');
        }

        User::makeUserLoggedIn($user);

        $this->removeOriginUser();

        return $this;
    }
}
