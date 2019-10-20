<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 LYRASOFT. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Admin\Form\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Package\AbstractPackage;
use Windwalker\Form\Form;

/**
 * The LoginDefinition class.
 *
 * @since  1.0
 */
class LoginDefinition extends AbstractFieldDefinition
{
    /**
     * Property package.
     *
     * @var  AbstractPackage
     */
    protected $warder;

    /**
     * WarderMethod constructor.
     *
     * @param AbstractPackage $warder
     */
    public function __construct(AbstractPackage $warder = null)
    {
        $this->warder = $warder ?: WarderHelper::getPackage();
    }

    /**
     * Define the form fields.
     *
     * @param Form $form The Windwalker form object.
     *
     * @return  void
     */
    public function doDefine(Form $form)
    {
        $loginName  = WarderHelper::getLoginName();
        $langPrefix = $this->warder->get('admin.language.prefix', 'warder.');

        $this->fieldset('login', function () use ($loginName, $langPrefix) {
            $fieldType = $loginName === 'email' ? 'email' : 'text';

            $this->$fieldType($loginName)
                ->label(__($langPrefix . 'user.field.' . $loginName))
                ->required(true);

            $this->password('password')
                ->label(__($langPrefix . 'user.field.password'))
                ->required(true);
        });

        $this->checkbox('remember')
            ->label(__($langPrefix . 'user.field.remember'));
    }
}
