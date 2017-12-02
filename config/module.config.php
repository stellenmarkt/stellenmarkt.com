<?php
namespace Gastro24;

use Gastro24\Options\Landingpages;
use Zend\ServiceManager\Factory\InvokableFactory;

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
            WordpressApi\Filter\PageIdMap::class => Factory\Filter\WpApiPageIdMapFactory::class,
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
             'content/regionen' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'content/staedte' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'jobs-by-mail/mail/jobs' => __DIR__ . '/../view/jobs-by-mail/mail/jobs.phtml',         
             'jobs-by-mail/mail/confirmation' => __DIR__ . '/../view/jobs-by-mail/mail/confirmation.phtml',   
         ],
    ],
    'translator'   => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
                'text_domain' => \Gastro24\Module::TEXT_DOMAIN,
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
                    'jobs' => [
                        'options' => [
                            'route' => '/job',
                        ],
                    ],
                    'jobboard' => [
                        'options' => [
                            'route' => '/jobs',
                        ]
                    ]
                ],
            ],
        ],
    ],

    'options' => [
        'Gastro24/WordpressApiOptions' => [
            'class' => WordpressApi\Options\WordpressApiOptions::class,
            'options' => [
                'baseUrl' => 'https://gastro24.yawik.org/blog/wp-json',
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
        Options\JobsearchQueries::class => [[
            'config' => [
                'Kategorien' => [
                    'Küche' => [
                        'Chefkoch' => 'q=Chefkoch+OR+Küchenchef',
                        'Koch/Köchin' => 'q=Koch'
                    ],
                    'Marketing' => [
                        'Business Manager' => 'q=Business+Manager',
                    ],
                ],
                'Regionen' => [
                    'Deutschland' => [
                        'Süd-Deutschland' => ',,region_MultiString=Hessen,Baden-Württemberg,Bayern',
                    ],
                ],
                'Städte' => [
                    'Deutschland' => [
                        'Süd-Deutschland' => ',,region_MultiString=Hessen,Baden-Württemberg,Bayern',
                    ],
                ],
            ]],
        ],
        Landingpages::class => [],
    ],

    'event_manager' => [

        'Core/ViewSnippets/Events' => [ 'listeners' => [
            WordpressApi\Listener\WordpressContentSnippet::class => ['wordpress-page', true],
        ]],
    ],

];
