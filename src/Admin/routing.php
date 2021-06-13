<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2019 __ORGANIZATION__.
 * @license    LGPL-2.0-or-later
 */

use Windwalker\Legacy\Core\Router\RouteCreator;

/** @var $router RouteCreator */

// User
$router->any('user', '/user(/id)')
    ->controller('User')
    ->extraValues([
        'layout' => 'user',
        'active' => [
            'mainmenu' => 'users'
        ],
    ]);

// Users
$router->any('users', '/users(/page)')
    ->controller('Users')
    ->postAction('CopyController')
    ->patchAction('BatchController')
    ->putAction('FilterController')
    ->extraValues([
        'layout' => 'users',
    ]);

// Login
$router->any('login', '/login')
    ->controller('User')
    ->getAction('LoginGetController')
    ->postAction('LoginSaveController')
    ->extraValues([
        'layout' => 'login',
        'warder' => [
            'require_login' => false
        ],
    ]);

// Logout
$router->any('logout', '/logout')
    ->controller('User')
    ->allActions('LogoutSaveController')
    ->extraValues([
        'warder' => [
            'require_login' => false
        ],
    ]);
