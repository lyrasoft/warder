<?php
/**
 * Part of warder project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Faker\Factory;
use Lyrasoft\Unidev\Helper\PravatarHelper;
use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Table\WarderTable;
use Lyrasoft\Warder\Warder;
use Windwalker\Core\Seeder\AbstractSeeder;
use Windwalker\Data\Data;

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
        $faker = Factory::create();

        $pass = Warder::hashPassword(1234);

        foreach (range(1, 50) as $i) {
            $data = new Data();

            $data->name        = $faker->name;
            $data->username    = $faker->userName;
            $data->email       = $faker->safeEmail;
            $data->password    = $pass;
            $data->avatar      = PravatarHelper::unique(600, uniqid($i, true));
            $data->group       = 'member';
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
