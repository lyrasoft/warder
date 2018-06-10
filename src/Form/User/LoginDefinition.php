<?php
/**
 * Part of phoenix project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

namespace Lyrasoft\Warder\Form\User;

use Lyrasoft\Warder\Helper\WarderHelper;
use Windwalker\Core\Form\AbstractFieldDefinition;
use Windwalker\Core\Language\Translator;
use Windwalker\Form\Form;

/**
 * The LoginDefinition class.
 *
 * @since  1.0
 */
class LoginDefinition extends AbstractFieldDefinition
{
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
        $langPrefix = WarderHelper::getPackage()->get('frontend.language.prefix', 'warder.');

        $this->fieldset('login', function () use ($loginName, $langPrefix) {
            $this->text($loginName)
                ->label(__($langPrefix . 'user.field.' . $loginName));

            $this->password('password')
                ->label(__($langPrefix . 'user.field.password'));
        });

        $this->checkbox('remember')
            ->label(__($langPrefix . 'user.field.remember'));
    }
}
