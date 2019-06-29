<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Repository;

use Lyrasoft\Warder\Admin\Record\UserRecord;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Warder;
use Phoenix\Repository\AdminRepository;
use Windwalker\Authentication\Authentication;
use Windwalker\Authentication\Credential;
use Windwalker\Core\DateTime\Chronos;
use Windwalker\Core\Repository\Exception\ValidateFailException;
use Windwalker\Core\User\Exception\AuthenticateFailException;
use Windwalker\Core\User\Exception\LoginFailException;
use Windwalker\Core\User\User;
use Windwalker\Data\Data;
use Windwalker\Data\DataInterface;

/**
 * The UserModel class.
 *
 * @since  1.0
 */
class UserRepository extends AdminRepository
{
    /**
     * Property name.
     *
     * @var  string
     */
    protected $name = 'user';

    /**
     * Property reorderConditions.
     *
     * @var  array
     */
    protected $reorderConditions = [];

    /**
     * getItem
     *
     * @param   mixed $pk
     *
     * @return  Data
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function getItem($pk = null)
    {
        $state = $this->state;

        $pk = $pk ?: $state['load.conditions'];

        return $this->once('item.' . json_encode($pk), function () use ($pk, $state) {
            if (!$pk) {
                return new UserData();
            }

            $item = User::get($pk);

            $this->postGetItem($item);

            return $item;
        });
    }

    /**
     * login
     *
     * @param string $account
     * @param string $password
     * @param bool   $remember
     * @param array  $options
     *
     * @return bool
     * @throws ValidateFailException
     */
    public function login($account, $password, $remember = false, $options = [])
    {
        $loginName = WarderHelper::getLoginName();

        $credential = new Credential();

        $credential->$loginName = $account;
        $credential->password   = $password;

        if (isset($options['provider'])) {
            $credential->_provider = $options['provider'];
        }

        try {
            $result = User::login($credential, (bool) $remember, $options);
        } catch (AuthenticateFailException $e) {
            $langPrefix = WarderHelper::getPackage()->get('admin.language.prefix', 'warder.');

            $messages = [];

            foreach ($e->getMessages() as $code) {
                switch ($code) {
                    case Authentication::USER_NOT_FOUND:
                        $messages[$code] = __($langPrefix . 'login.message.user.not.found');
                        break;

                    case Authentication::EMPTY_CREDENTIAL:
                        $messages[$code] = __($langPrefix . 'login.message.empty.credential');
                        break;

                    case Authentication::INVALID_CREDENTIAL:
                        $messages[$code] = __($langPrefix . 'login.message.invalid.credential');
                        break;

                    case Authentication::INVALID_PASSWORD:
                        $messages[$code] = __($langPrefix . 'login.message.invalid.password');
                        break;

                    case Authentication::INVALID_USERNAME:
                        $messages[$code] = __($langPrefix . 'login.message.invalid.username');
                        break;

                    default:
                        $messages[$code] = $code;
                }
            }

            throw new LoginFailException(
                $messages[array_key_first($messages)],
                $messages,
                401,
                $e
            );
        }

        return $result;
    }

    /**
     * save
     *
     * @param DataInterface|UserRecord $user
     *
     * @return DataInterface|UserRecord
     * @throws \Exception
     */
    public function save(DataInterface $user)
    {
        if ('' !== (string) $user->password) {
            $user->password = Warder::hashPassword($user->password);
        } else {
            unset($user->password);
        }

        unset($user->password2);

        $key = $this->getKeyName();

        $user->_isNew = !$user->$key;

        $this->prepareDefaultData($user);

        $user->bind(User::save($user));

        return $user;
    }

    /**
     * getDefaultData
     *
     * @return array
     */
    public function getFormDefaultData()
    {
        $item = parent::getFormDefaultData();

        unset($item['password'], $item['password2']);

        return $item;
    }

    /**
     * prepareDefaultData
     *
     * @param   DataInterface|UserRecord $user
     *
     * @return  void
     * @throws \Exception
     */
    protected function prepareDefaultData(DataInterface $user)
    {
        $date = new Chronos();

        if (!$user->id) {
            $user->registered = $date->toSql();
        } else {
            $user->modified = $date->toSql();
        }
    }
}
