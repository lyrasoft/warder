<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

use Faker\Factory;
use Lyrasoft\Unidev\Helper\PravatarHelper;
use Lyrasoft\Warder\Admin\DataMapper\UserMapper;
use Lyrasoft\Warder\Data\UserData;
use Lyrasoft\Warder\Table\WarderTable;
use Windwalker\Core\Migration\AbstractMigration;
use Windwalker\Core\Security\Hasher;
use Windwalker\Database\Schema\Schema;

/**
 * Migration class, version: 20160210041557
 */
class UserInit extends AbstractMigration
{
    /**
     * Migrate Up.
     */
    public function up()
    {
        $this->createTable(WarderTable::USERS, function (Schema $schema) {
            $schema->primary('id')->comment('Primary Key');
            $schema->varchar('name')->comment('Full Name');
            $schema->varchar('username')->comment('Login name');
            $schema->varchar('email')->comment('Email');
            $schema->varchar('password')->comment('Password');
            $schema->varchar('avatar')->comment('Avatar');
            $schema->varchar('group')->comment('Group');
            $schema->tinyint('blocked')->length(1)->comment('0: normal, 1: blocked');
            $schema->varchar('activation')->comment('Activation code.');
            $schema->varchar('reset_token')->comment('Reset Token');
            $schema->datetime('last_reset')->comment('Last Reset Time');
            $schema->datetime('last_login')->comment('Last Login Time');
            $schema->datetime('registered')->comment('Register Time');
            $schema->datetime('modified')->comment('Modified Time');
            $schema->text('params')->comment('Params');

            $schema->addIndex('id');
            $schema->addIndex('username');
            $schema->addIndex('email');
            $schema->addIndex('group');
        });

        $this->createTable(WarderTable::USER_SOCIALS, function (Schema $schema) {
            $schema->integer('user_id')->comment('User ID');
            $schema->varchar('identifier')->comment('User identifier name');
            $schema->char('provider')->length(15)->comment('Social provider');

            $schema->addIndex('user_id');
            $schema->addIndex('identifier');
        });

        $faker = Factory::create();

        $user = new UserData();

        $user->email      = 'admin@windwalker.io';
        $user->name       = 'Super User';
        $user->username   = 'admin';
        $user->avatar     = PravatarHelper::unique(400, uniqid('', true));
        $user->password   = Hasher::create('pass1234');
        $user->blocked    = 0;
        $user->activation = '';
        $user->last_reset = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->last_login = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->registered = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->modified   = $faker->dateTimeThisYear->format($this->getDateFormat());

        UserMapper::createOne($user);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->drop(WarderTable::USERS);
        $this->drop(WarderTable::USER_SOCIALS);
    }
}
