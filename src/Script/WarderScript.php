<?php
/**
 * Part of earth project.
 *
 * @copyright  Copyright (C) 2019 .
 * @license    LGPL-2.0-or-later
 */

namespace Lyrasoft\Warder\Script;

use Lyrasoft\Warder\WarderPackage;
use Phoenix\Script\PhoenixScript;
use Windwalker\Legacy\Core\Asset\AbstractScript;

/**
 * The WarderScript class.
 *
 * @since  1.7.3
 */
class WarderScript extends AbstractScript
{
    /**
     * Property packageClass.
     *
     * @var  string
     */
    protected static $packageClass = WarderPackage::class;

    /**
     * accountCheckValidation
     *
     * @param string|null $selector
     * @param array       $options
     *
     * @return  void
     *
     * @since  1.7.3
     */
    public static function accountCheckValidation(?string $selector = null, array $options = []): void
    {
        if (!static::inited(__METHOD__)) {
            PhoenixScript::phoenix();
            PhoenixScript::translate('warder.message.user.account.exists');
            PhoenixScript::addRoute(
                'user_account_check',
                static::getRouter()->to('front@user_account_check')
            );

            static::addJS(static::packageName() . '/js/account-check.min.js');
        }

        if ($selector && !static::inited(__METHOD__, get_defined_vars())) {
            $optStr = static::getJSObject([
                // Default
            ], $options);

            $js = <<<JS
$('$selector').userAccountCheck($optStr);
JS;

            PhoenixScript::domready($js);
        }
    }
}
