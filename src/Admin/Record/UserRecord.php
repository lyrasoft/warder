<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Record;

use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Admin\DataMapper\UserSocialMapper;
use Lyrasoft\Warder\Admin\Record\Traits\UserDataTrait;
use Lyrasoft\Warder\Helper\WarderHelper;
use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\Core\Language\Translator;
use Windwalker\Event\Event;
use Windwalker\Record\Record;

/**
 * The UserRecord class.
 *
 * @since  1.0
 */
class UserRecord extends Record
{
    use UserDataTrait;

    /**
     * Property table.
     *
     * @var  string
     */
    protected $table = WarderTable::USERS;

    /**
     * Property keys.
     *
     * @var  string
     */
    protected $keys = 'id';

    /**
     * check
     *
     * @return  static
     * @throws \InvalidArgumentException
     */
    public function check()
    {
        $loginName = WarderHelper::getLoginName();

        if (!$this->$loginName) {
            throw new \InvalidArgumentException(
                Translator::sprintf(
                    'warder.user.save.message.account.empty',
                    __('warder.user.field.' . $loginName)
                )
            );
        }

        $exists = UserMapper::findOne([$loginName => $this->$loginName]);

        if ($exists->notNull() && $this->id != $exists->id) {
            throw new \InvalidArgumentException(Translator::sprintf('warder.user.save.message.exists', $loginName,
                $this->$loginName));
        }

        if ($this->email) {
            $exists = UserMapper::findOne(['email' => $this->email]);

            if ($exists->notNull() && $this->id != $exists->id) {
                throw new \InvalidArgumentException(Translator::sprintf('warder.user.save.message.exists', $loginName,
                    $this->email));
            }
        }

        return $this;
    }

    /**
     * onAfterLoad
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterLoad(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterStore
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterStore(Event $event)
    {
        // Add your logic
    }

    /**
     * onAfterDelete
     *
     * @param Event $event
     *
     * @return  void
     */
    public function onAfterDelete(Event $event)
    {
        UserSocialMapper::delete(['user_id' => $this->id]);
    }
}
