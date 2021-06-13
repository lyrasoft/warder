<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Admin\Field\User;

use Lyrasoft\Warder\Warder;
use Windwalker\Legacy\Form\Field\ListField;
use Windwalker\Legacy\Html\Option;

/**
 * The UserGroupField class.
 *
 * @since  1.7.3
 */
class UserGroupField extends ListField
{
    /**
     * prepareOptions
     *
     * @return  array|Option[]
     */
    protected function prepareOptions()
    {
        $options = [];

        foreach (Warder::getGroups() as $name => $group) {
            $options[] = new Option(
                __($group['title'] ?? $name),
                $name
            );
        }

        return $options;
    }
}
