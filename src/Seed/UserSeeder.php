<?php
/**
 * Part of warder project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Table\WarderTable;
use Lyrasoft\Warder\Warder;
use Windwalker\Legacy\Core\Seeder\AbstractSeeder;
use Windwalker\Legacy\Data\Data;

/**
 * The UserSeeder class.
 *
 * @since  1.0
 */
class UserSeeder extends AbstractSeeder
{
    /**
     * doExecute
     *
     * @return  void
     */
    public function doExecute()
    {
        $faker = $this->faker->create();
        $defaultMember = Warder::getWarderPackage()->get('user.default_group', 'member');

        $pass = Warder::hashPassword(1234);

        foreach (range(1, 50) as $i) {
            $data = new Data();

            $data->name        = $faker->name;
            $data->username    = $faker->userName;
            $data->email       = $faker->safeEmail;
            $data->password    = $pass;
            $data->avatar      = $faker->avatar(600, uniqid($i, true));
            $data->group       = $defaultMember;
            $data->blocked     = 0;
            $data->activation  = '';
            $data->reset_token = '';
            $data->last_reset  = $this->getNullDate();
            $data->last_login  = $faker->dateTimeThisYear->format($this->getDateFormat());
            $data->registered  = $faker->dateTimeThisYear->format($this->getDateFormat());
            $data->modified    = $faker->dateTimeThisYear->format($this->getDateFormat());
            $data->params      = '';

            UserMapper::createOne($data);

            $this->outCounting();
        }
    }

    /**
     * doClear
     *
     * @return  void
     */
    public function doClear()
    {
        $this->truncate(WarderTable::USERS);
    }
}
