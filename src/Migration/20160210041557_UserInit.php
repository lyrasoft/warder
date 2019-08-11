<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2014 - 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later;
 */

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
            $schema->tinyint('receive_mail')->defaultValue(0)->length(1);
            $schema->varchar('activation')->comment('Activation code.');
            $schema->varchar('reset_token')->comment('Reset Token');
            $schema->datetime('last_reset')->comment('Last Reset Time');
            $schema->datetime('last_login')->comment('Last Login Time');
            $schema->datetime('registered')->comment('Register Time');
            $schema->datetime('modified')->comment('Modified Time');
            $schema->text('params')->comment('Params');

            $schema->addIndex('username(150)');
            $schema->addIndex('email(150)');
            $schema->addIndex('group(150)');
        });

        $this->createTable(WarderTable::USER_SOCIALS, function (Schema $schema) {
            $schema->integer('user_id')->comment('User ID');
            $schema->varchar('identifier')->comment('User identifier name');
            $schema->char('provider')->length(15)->comment('Social provider');

            $schema->addIndex('user_id');
            $schema->addIndex('identifier(150)');
        });

        $this->createTable(WarderTable::SESSIONS, function (Schema $schema) {
            $schema->varchar('id')->length(192);
            $schema->text('data');
            $schema->integer('user_id');
            $schema->integer('time');

            $schema->addIndex('id(150)');
            $schema->addIndex('user_id');
            $schema->addIndex('time');
        });

        $faker = $this->faker->create();

        $user = new UserData();

        $user->email        = 'webadmin@simular.co';
        $user->name         = 'Simular';
        $user->username     = 'admin';
        $user->avatar       = 'https://avatars0.githubusercontent.com/u/13175487';
        $user->password     = Hasher::create('1234');
        $user->blocked      = 0;
        $user->receive_mail = 1;
        $user->group        = 'admin';
        $user->activation   = '';
        $user->last_reset   = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->last_login   = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->registered   = $faker->dateTimeThisYear->format($this->getDateFormat());
        $user->modified     = $faker->dateTimeThisYear->format($this->getDateFormat());

        UserMapper::createOne($user);
    }

    /**
     * Migrate Down.
     */
    public function down()
    {
        $this->drop(WarderTable::USERS);
        $this->drop(WarderTable::USER_SOCIALS);
        $this->drop(WarderTable::SESSIONS);
    }
}
