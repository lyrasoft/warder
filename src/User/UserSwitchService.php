<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\User;

use Lyrasoft\Warder\Data\WarderUserDataInterface;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\User\User;
use Windwalker\Session\Session;

/**
 * The UserSwitchService class.
 *
 * @since  1.7
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
     * @since  1.7
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
     * @since  1.7
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
     * @since  1.7
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
     * @since  1.7
     */
    public function removeOriginUser(): self
    {
        $this->session->remove(static::ORIGIN_USER_SESSION_KEY);
        $this->session->remove('keepgroup');

        return $this;
    }

    /**
     * switch
     *
     * @param  WarderUserDataInterface  $targetUser
     * @param  array                    $options
     *
     * @return  static
     *
     * @since  1.7
     */
    public function switch(WarderUserDataInterface $targetUser, array $options = []): self
    {
        $user = $this->getOriginUser() ?: Warder::getUser();

        unset($targetUser->password);

        if ($options['keepgroup'] ?? true) {
            $groupField = $options['group_field'] ?? 'group';
            $targetUser->$groupField = $user[$groupField];

            $this->session->set('keepgroup', $targetUser->$groupField);
        }

        $this->setOriginUser($user);

        User::makeUserLoggedIn($targetUser);

        return $this;
    }

    /**
     * frontendLogin
     *
     * @param  WarderUserDataInterface  $targetUser
     * @param  array                    $options
     *
     * @return  static
     *
     * @since  1.7.14
     */
    public function frontendLogin(WarderUserDataInterface $targetUser, array $options = []): self
    {
        $user = $this->getOriginUser() ?: Warder::getUser();

        $backup = $_SESSION;

        unset($targetUser->password);

        if ($options['keepgroup'] ?? true) {
            $groupField = $options['group_field'] ?? 'group';
            $targetUser->$groupField = $user[$groupField];

            $this->session->set('keepgroup', $targetUser->$groupField);
        }

        $warder = WarderHelper::getPackage();

        $sessName = $warder->get('session_separate.admin_session_name');
        $currentId = $this->session->getId();

        $this->session->close();
        $this->session->getBridge()->setName('PHPSESSID');
        $this->session->start();
        $this->session->clean();
        $this->session->regenerate();
        $this->session->set('user', $targetUser);

        $this->session->close();

        $this->session->getBridge()->setName($sessName);
        $this->session->getBridge()->setId($currentId);
        $this->session->start();

        $_SESSION = $backup;

        foreach ($this->session->getBags() as $name => $bag) {
            if (isset($_SESSION['_' . $name])) {
                $bag->setData($_SESSION['_' . $name]);
            }
        }

        return $this;
    }

    /**
     * recover
     *
     * @return  static
     *
     * @since  1.7
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
