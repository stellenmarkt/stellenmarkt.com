<?php


\Gastro24\Module::$isLoaded = true;

/**
 * create a config/autoload/Gastro24.global.php and put modifications there
 */

return [
    'service_manager' => [
        'factories' => [
            'Auth/Dependency/Manager' => 'Gastro24\Factory\Dependency\ManagerFactory',
            \Gastro24\WordpressApi\Service\WordpressClient::class => \Gastro24\WordpressApi\Factory\Service\WordpressClientFactory::class,
            \Gastro24\WordpressApi\Listener\WordpressContentSnippet::class => \Gastro24\WordpressApi\Factory\Listener\WordpressContentSnippetFactory::class,
        ],
    ],
    'view_manager' => [
                 'template_map' => [
                     'layout/layout' => __DIR__ . '/../view/layout.phtml',
                     'layout/application-form' => __DIR__ . '/../view/application-form.phtml',
                     'core/index/index' => __DIR__ . '/../view/index.phtml',
                     'piwik' => __DIR__ . '/../view/piwik.phtml',
                     'jobs/jobboard/index.ajax.phtml' => __DIR__ . '/../view/jobs/index.ajax.phtml',
                     'jobs/jobboard/index' => __DIR__ . '/../view/jobs/index.phtml',
                     'main-navigation' => __DIR__ . '/../view/main-navigation.phtml',
                     'auth/index/login-info' => __DIR__ . '/../view/login-info.phtml',
                     'auth/users/list.ajax.phtml' => __DIR__ . '/../view/auth/users/list.ajax.phtml', // hide email adresses, since this is is a public demo
                      ],
             ],
             'translator' => [
                 'translation_file_patterns' => [
                      [
                          'type' => 'gettext',
                           'base_dir' => __DIR__ . '/../language',
                           'pattern' => '%s.mo',
                            ],
                      ],
                 ],


             'form_elements' => [
                 'invokables' => [
                     'Jobs/Description' => 'Gastro24\Form\JobsDescription',
                 ],
             ],
    'router' => [
        'routes' => [
            'lang' => [
                'options' => [
                    'defaults' => [
                        'controller' => 'Jobs/Jobboard', //Overwrites the route of the start Page
                        'action'     => 'index',
                    ],
                ],
            ],
        ],
    ],

    'options' => [
        'Gastro24/WordpressApiOptions' => [
            'class' => \Gastro24\WordpressApi\Options\WordpressApiOptions::class,
            'options' => [
                'baseUrl' => 'https://gastro24.yawik.org/blog/wp-json/wp/v2',
                'httpClientOptions' => [
                    'auth' => ['gastro', 'jobs.ch'],
                ],
            ],
        ],
        \Gastro24\WordpressApi\Options\WordpressContentSnippetOptions::class => [
            'options' => [
                'idMap' => [
                    'hotelfachmann' => 2,
                    'koch' => 6,
                ],
            ],
        ],
    ],

    'event_manager' => [

        'Core/ViewSnippets/Events' => [ 'listeners' => [
            \Gastro24\WordpressApi\Listener\WordpressContentSnippet::class => ['wordpress-page', true],
        ]],
    ],

];
