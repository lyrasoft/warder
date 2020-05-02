<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2015 LYRASOFT. All rights reserved.
 * @license    GNU Lesser General Public License version 3 or later.
 */

namespace Lyrasoft\Warder\Handler;

use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Admin\Record\UserRecord;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Repository\UserRepository;
use Lyrasoft\Warder\WarderPackage;
use Windwalker\Core\Cache\RuntimeCacheTrait;
use Windwalker\Core\Mailer\Punycode;
use Windwalker\Core\Package\Resolver\RecordResolver;
use Windwalker\Core\User\UserDataInterface;
use Windwalker\Core\User\UserHandlerInterface;
use Windwalker\Record\Exception\NoResultException;
use Windwalker\Record\Record;
use Windwalker\Session\Session;

/**
 * The UserHandler class.
 *
 * @since  2.1.1
 */
class UserHandler implements UserHandlerInterface
{
    use RuntimeCacheTrait;

    /**
     * Property package.
     *
     * @var  WarderPackage
     */
    protected $warder;

    /**
     * Property key.
     *
     * @var  string
     */
    protected $pk = 'id';

    /**
     * UserHandler constructor.
     *
     * @param WarderPackage $package
     */
    public function __construct(WarderPackage $package)
    {
        $this->warder = $package;
    }

    /**
     * load
     *
     * @param array|object $conditions
     *
     * @return  UserDataInterface
     * @throws \Exception
     * @throws \Psr\Cache\InvalidArgumentException
     */
    public function load($conditions)
    {
        if (is_object($conditions)) {
            $conditions = get_object_vars($conditions);
        }

        if (!$conditions) {
            $user = $this->once('current.user', function () {
                /** @var Session $session */
                $session = $this->warder->getContainer()->get('session');

                $user = (array) $session->get($this->warder->get('user.session_name', 'user'));

                // If user is logged-in, get user data from DB to refresh info.
                if ($user[$this->pk] ?? null) {
                    $userData = UserMapper::findOne([$this->pk => $user[$this->pk]]);

                    if ($userData->notNull()) {
                        unset($userData->password);
                        $user = $userData->dump();

                        $group = $session->get('keepgroup');

                        if ($group) {
                            $user['group'] = $group;
                        }
                    }
                }

                return $user;
            });
        } else {
            $user = $this->getRecord();

            if (isset($conditions['email'])) {
                $conditions['email'] = Punycode::toAscii($conditions['email']);
            }

            try {
                $user->load($conditions);

                $user = $user->dump(true);
            } catch (NoResultException $e) {
                $user = [];
            }
        }

        $class = $this->warder->get('class.data', UserData::class);
        $user  = new $class((array) $user);

        if (isset($user->email)) {
            $user->email = Punycode::toUtf8($user->email);
        }

        return $user;
    }

    /**
     * create
     *
     * @param UserDataInterface|UserData $user
     *
     * @return  UserData
     * @throws \Exception
     */
    public function save(UserDataInterface $user)
    {
        $record = $this->getRecord();

        if ($user->email !== null) {
            $user->email = Punycode::toAscii($user->email);
        }

        $key = $this->pk;

        if ($user->$key) {
            $record->load($user->$key)
                ->bind($user->dump())
                ->check()
                ->store(true);
        } else {
            $record->bind($user->dump())
                ->check()
                ->store(true);
        }

        $user->$key = $record->$key;

        return $user;
    }

    /**
     * delete
     *
     * @param array $conditions
     *
     * @return  boolean
     * @throws \Exception
     */
    public function delete($conditions)
    {
        $this->getRecord()->delete($conditions);

        return true;
    }

    /**
     * login
     *
     * @param UserDataInterface|UserData $user
     *
     * @return  boolean
     * @throws \RuntimeException
     */
    public function login(UserDataInterface $user)
    {
        $session = $this->warder->getCurrentPackage()->app->session;

        unset($user->password);

        $session->set($this->warder->get('user.session_name', 'user'), $user->dump(true));

        $this->resetCache();

        return true;
    }

    /**
     * logout
     *
     * @param UserDataInterface|UserData $user
     *
     * @return bool
     */
    public function logout(UserDataInterface $user = null)
    {
        $session = $this->warder->getCurrentPackage()->app->session;

        $session->destroy();
        $session->restart();

        $this->resetCache();

        return true;
    }

    /**
     * getDataMapper
     *
     * @return  UserRecord|Record
     * @throws \Exception
     */
    protected function getRecord()
    {
        $record = RecordResolver::create('User');

        if (!$record) {
            throw new \DomainException(sprintf(
                'Record: User not found, Namespaces: %s',
                implode(" |\n", RecordResolver::dumpNamespaces())
            ));
        }

        return $record;
    }

    /**
     * Method to get property Pk
     *
     * @return  string
     *
     * @since  1.6.2
     */
    public function getPk()
    {
        return $this->pk;
    }

    /**
     * Method to set property pk
     *
     * @param   string $pk
     *
     * @return  static  Return self to support chaining.
     *
     * @since  1.6.2
     */
    public function setPk($pk)
    {
        $this->pk = $pk;

        return $this;
    }
}
