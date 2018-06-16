<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

use Windwalker\Core\Seeder\AbstractSeeder;

/**
 * The DatabaseSeeder class.
 *
 * @since  1.0
 */
class MainSeeder extends AbstractSeeder
{
    /**
     * doExecute
     *
     * @return  void
     * @throws ReflectionException
     */
    public function doExecute()
    {
        $this->execute(UserSeeder::class);
    }

    /**
     * doClear
     *
     * @return  void
     * @throws ReflectionException
     */
    public function doClear()
    {
        $this->clear(UserSeeder::class);
    }
}
