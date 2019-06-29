<?php
/**
 * Part of warder project.
 *
 * @copyright  Copyright (C) 2016 {ORGANIZATION}. All rights reserved.
 * @license    GNU General Public License version 2 or later.
 */

return [
    /*
     * User account settings.
     * ------------------------------------------------
     */
    'user' => [
        'login_name' => 'username',
        'default_group' => 'member'
    ],

    /*
     * Database table name.
     * ------------------------------------------------
     */
    'table' => [
        'users' => 'users',
        'user_socials' => 'user_socials',
        'groups' => 'groups',
        'user_group_maps' => 'user_group_maps',
        'actions' => 'actions',
    ],

    /*
     * The Authentication methods
     * ------------------------------------------------
     */
    'methods' => [
        'warder' => \Lyrasoft\Warder\Authentication\Method\WarderMethod::class,
    ],

    /*
     * Separate Admin & Frontend session name.
     * ------------------------------------------------
     */
    'session_separate' => [
        'enabled' => false,
        'admin_session_name' => 'PHPSESSID_ADMIN'
    ],

    /*
     * Frontend package settings
     * ------------------------------------------------
     */
    'frontend' => [
        /*
         * The frontend packages
         * @var  string|array
         */
        'package' => ['front', 'main'],
        'view' => [
            'extends' => '_global.html',
            'noauth_extends' => '_global.html',
        ],
        'redirect' => [
            'login' => 'home',
            'logout' => 'login',
            'forget' => 'login',
        ],
        'login' => [
            'return_key' => 'return',
        ],
        'language' => [
            'prefix' => 'warder.',
        ],
    ],

    /*
     * User groups.
     * ------------------------------------------------
     */
    'groups' => [
        'member' => [
            'title' => 'warder.group.member',
            'is_admin' => false
        ],
        'admin' => [
            'title' => 'warder.group.admin',
            'is_admin' => true
        ],
    ],

    /*
     * Backend package settings.
     * ------------------------------------------------
     */
    'admin' => [
        /*
         * The backend packages
         * @var  string|array
         */
        'package' => ['admin'],
        'view' => [
            'extends' => '_global.admin.admin',
            'noauth_extends' => '_global.admin.admin',
        ],
        'redirect' => [
            'login' => 'home',
            'logout' => 'login',
        ],
        'login' => [
            'return_key' => 'return',
        ],
        'language' => [
            'prefix' => 'warder.',
        ],
    ],

    /*
     * User Handler Classes.
     * ------------------------------------------------
     */
    'class' => [
        'handler' => \Lyrasoft\Warder\Handler\UserHandler::class,
        'data' => \Lyrasoft\Warder\Data\UserData::class,
    ],

    'listeners' => [
        \Lyrasoft\Warder\Listener\UserListener::class,
        \Lyrasoft\Warder\Listener\WarderListener::class,
    ],
];
