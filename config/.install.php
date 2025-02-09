<?php
/**
 * Этот файл является частью модуля веб-приложения GearMagic.
 * 
 * Файл конфигурации установки модуля.
 * 
 * @link https://gearmagic.ru
 * @copyright Copyright (c) 2015 Веб-студия GearMagic
 * @license https://gearmagic.ru/license/
 */

return [
    'use'         => FRONTEND,
    'id'          => 'gm.fe.site',
    'name'        => 'Web Site', 
    'description' => 'Publishing web site pages',
    'namespace'   => 'Gm\Frontend\Site',
    'path'        => '/gm/gm.fe.site',
    'shortcodes'  => [
        'html-title', 'html-meta', 'html-begin', 'html-end', 'html-load', 'html-ready', 'html-head'
    ],
    'route'  => 'site',
    'routes' => [
        [
            'use'     => BACKEND,
            'type'    => 'crudSegments',
            'options' => [
                'module' => 'gm.fe.site',
                'route'  => 'site',
                'prefix' => BACKEND
            ]
        ]
    ],
    'locales'     => ['ru_RU', 'en_GB'],
    'permissions' => [],
    'events'      => [],
    'required'    => [
        ['php', 'version' => '8.2'],
        ['app', 'code' => 'GM MS'],
        ['app', 'code' => 'GM CMS']
    ]
];
