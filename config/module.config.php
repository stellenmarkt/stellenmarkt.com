<?php
namespace Gastro24;

Module::$isLoaded = true;

/**
 * create a config/autoload/Gastro24.global.php and put modifications there
 */

$idMap = [
    'hotelfachmann' => 2,
    'koch' => 7,
    'cuisinier' => 23,
    'barkeeper' => 43,
    'rezeption' => 45,
    'konditor' => 48,
];

return [
    'service_manager' => [
        'factories' => [
            'Auth/Dependency/Manager' => 'Gastro24\Factory\Dependency\ManagerFactory',
            WordpressApi\Service\WordpressClient::class => WordpressApi\Factory\Service\WordpressClientFactory::class,
            WordpressApi\Listener\WordpressContentSnippet::class => WordpressApi\Factory\Listener\WordpressContentSnippetFactory::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\WordpressPageController::class => Factory\Controller\WordpressPageControllerFactory::class,
        ],
    ],

    'filters' => [
        'factories' => [
            WordpressApi\Filter\PageIdMap::class => WordpressApi\Factory\Filter\PageIdMapFactory::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            WordpressApi\View\Helper\WordpressContent::class => WordpressApi\Factory\View\Helper\WordpressContentFactory::class,
            View\Helper\LandingpagesList::class => Factory\View\Helper\LandingpagesListFactory::class,
        ],
        'aliases' => [
            'wordpress' => WordpressApi\View\Helper\WordpressContent::class,
            'landingpages' => View\Helper\LandingpagesList::class,
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
                     'gastro24/wordpress-page/index' => __DIR__ . '/../view/gastro24/wordpress-page/index.phtml',
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
                'child_routes' => [
                    'wordpress' => [
                        'type' => 'Segment',
                        'options' => [
                            'route' => '/wp/:type/:id',
                            'defaults' => [
                                'controller' => Controller\WordpressPageController::class,
                                'action' => 'index',
                            ],
                            'constraints' => [
                                'type' => '(page|post)',
                            ]
                        ],
                    ],
                ],
            ],
        ],
    ],

    'options' => [
        'Gastro24/WordpressApiOptions' => [
            'class' => WordpressApi\Options\WordpressApiOptions::class,
            'options' => [
                'baseUrl' => 'https://gastro24.yawik.org/blog/wp-json/wp/v2',
                'httpClientOptions' => [
                    'auth' => ['gastro', 'jobs.ch'],
                ],
                'idMap' => $idMap
            ],
        ],
        WordpressApi\Options\WordpressContentSnippetOptions::class => [
            'options' => [
                'idMap' => $idMap
            ],
        ],
    ],

    'event_manager' => [

        'Core/ViewSnippets/Events' => [ 'listeners' => [
            WordpressApi\Listener\WordpressContentSnippet::class => ['wordpress-page', true],
        ]],
    ],

];
