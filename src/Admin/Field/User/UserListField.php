<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2015 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Field\User;

use Lyrasoft\Warder\Admin\Table\Table;
use Lyrasoft\Warder\Helper\WarderHelper;
use Phoenix\Field\ItemListField;

/**
 * The UserField class.
 *
 * @since  1.0
 */
class UserListField extends ItemListField
{
    /**
     * Property ordering.
     *
     * @var  string
     */
    protected $ordering = null;

    /**
     * Property textuser.field.
     *
     * @var  string
     */
    protected $textField = 'name';

    /**
     * buildInput
     *
     * @param array $attrs
     *
     * @return  mixed|void
     */
    public function buildInput($attrs)
    {
        $warder      = WarderHelper::getPackage();
        $this->table = $warder->get('table.users', 'username');

        return parent::buildInput($attrs);
    }
}
