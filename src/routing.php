<?php
/**
 * Part of Windwalker project.
 *
 * @copyright  Copyright (C) 2019 __ORGANIZATION__.
 * @license    __LICENSE__
 */

use Windwalker\Core\Router\RouteCreator;

/** @var $router RouteCreator */

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

// Profile
$router->any('profile', '/profile/(id)')
    ->controller('User\Profile')
    ->getAction('GetController')
    ->extraValues([
        'layout' => 'profile',
    ]);

// Profile Edit
$router->any('profile_edit', '/profile')
    ->controller('User\Profile')
    ->extraValues([
        'layout' => 'edit',
        'warder' => [
            'require_login' => true
        ],
    ]);

// Registration
$router->any('registration', '/registration')
    ->controller('User\Registration')
    ->getAction('RegistrationGetController')
    ->postAction('RegistrationSaveController')
    ->extraValues([
        'layout' => 'registration',
        'warder' => [
            'require_login' => false
        ],
    ]);

// Registration Activate
$router->any('registration_activate', '/registration/activate')
    ->controller('User\Registration')
    ->allActions('ActivateSaveController')
    ->extraValues([
        'warder' => [
            'require_login' => false
        ],
    ]);

// Social Login
$router->any('social_login', '/social-login/(provider)')
    ->controller('User')
    ->allActions('LoginSaveController')
    ->extraValues([
        'warder' => [
            'require_login' => false
        ],
    ]);

// Social Auth
$router->any('social_auth', '/social-auth')
    ->controller('User')
    ->allActions('AuthController')
    ->extraValues([
        'warder' => [
            'require_login' => false
        ],
    ]);

// Forget Request
$router->any('forget_request', '/forget/request')
    ->controller('User\Forget')
    ->getAction('RequestGetController')
    ->postAction('RequestSaveController')
    ->extraValues([
        'layout' => 'forget.request',
        'warder' => [
            'require_login' => false
        ],
    ]);

// Forget Confirm
$router->any('forget_confirm', '/forget/confirm')
    ->controller('User\Forget')
    ->getAction('ConfirmGetController')
    ->postAction('ConfirmSaveController')
    ->extraValues([
        'layout' => 'forget.confirm',
        'warder' => [
            'require_login' => false
        ],
    ]);

// Forget Reset
$router->any('forget_reset', '/forget/reset')
    ->controller('User\Forget')
    ->getAction('ResetGetController')
    ->postAction('ResetSaveController')
    ->extraValues([
        'layout' => 'forget.reset',
        'warder' => [
            'require_login' => false
        ],
    ]);

// Forget Complete
$router->any('forget_complete', '/forget/complete')
    ->controller('User\Forget')
    ->getAction('CompleteGetController')
    ->postAction('ComleteSaveController')
    ->extraValues([
        'layout' => 'forget.complete',
        'warder' => [
            'require_login' => false
        ],
    ]);
