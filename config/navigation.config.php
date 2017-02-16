<?php
/**
 * Created by PhpStorm.
 * User: cbleek
 * Date: 13.02.16
 * Time: 12:45
 */

return [
    /**
     * Identifier of the navigation configuration. This must be called "navigation"
     */
    'navigation' => [
        /**
         * Identifies a navigation.
         */
        'default' => [
            'settings' => [
                'pages' => [
                    'my-profile' => [
                        'label' => /*@translate */ 'My profile',
                        'route' => 'lang/my',
                        'order' => 20,
                    ],
                    'my-password' => [
                        'label' => /*@translate */ 'Change password',
                        'route' => 'lang/my-password',
                        'order' => 30,
                    ],
                    'my-organization' => [
                        'label' => /*@translate */ 'My organization',
                        'route' => 'lang/my-organization',
                        'order' => 30,
                    ],
                ]
            ],
            'login' => [
                'label' => 'Login',
                'route' => 'lang/auth',
                'order' => 50
            ],
            'jobmail' => [
                'label' => 'Jobmail abonnieren',
                'route' => 'lang/auth',
                'order' => 40
            ],
            'post-a-job' => [
                'label' => 'Stellenanzeige schalten',
                'route' => 'lang/jobboard',
                'order' => 60,
                'class' => 'inverted'
            ]
        ],
    ],

    /**
     * if you want to completely hide the Applications fom the Menu, you can do so by ACL. Lookup the identifier
     * for the resource of the "Application" menu in the applications Module. Deny the Access for recruiters.
     */
    'acl' => [
        'rules' => [
            'recruiter' => [
                'deny' => [
                    'route/lang/applications',
                ],
            ],
        ],
    ],
    /**
     * Modules can implement SettingEntities. If they do so, they will be automatically inserted into the navigation.
     * If you want to disable this feature, you can unset Modules Settings in your configuration
     */
    'Applications' => [
        'settings' => null,
    ],
    'Core' => [
        'settings' => null,
    ],
];
