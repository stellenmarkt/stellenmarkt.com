<?php
namespace Gastro24;

use Gastro24\Options\Landingpages;
use Jobs\Listener\Events\JobEvent;
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

    'doctrine' => [
        'driver' => [
            'odm_default' => [
                'drivers' => [
                    'Gastro24\Entity' => 'annotation',
                ],
            ],
            'annotation' => [
                /*
                 * All drivers (except DriverChain) require paths to work on. You
                 * may set this value as a string (for a single path) or an array
                 * for multiple paths.
                 * example https://github.com/doctrine/DoctrineORMModule
                 */
                'paths' => [ __DIR__ . '/../src/Entity'],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'Auth/Dependency/Manager' => 'Gastro24\Factory\Dependency\ManagerFactory',
            WordpressApi\Service\WordpressClient::class => WordpressApi\Factory\Service\WordpressClientFactory::class,
            WordpressApi\Listener\WordpressContentSnippet::class => WordpressApi\Factory\Listener\WordpressContentSnippetFactory::class,
            Listener\UserRegisteredListener::class => Listener\UserRegisteredListenerFactory::class,
            Listener\VoidListener::class => InvokableFactory::class,
            Listener\CreateJobOrder::class => Listener\CreateJobOrderFactory::class,
        ],
        'aliases' => [
            'Orders\Form\Listener\InjectInvoiceAddressInJobContainer' => Listener\VoidListener::class,
            'Orders\Form\Listener\ValidateJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders\Form\Listener\DisableJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders/Listener/BindInvoiceAddressEntity' => Listener\VoidListener::class,
            'Orders/Listener/CreateJobOrder' => Listener\CreateJobOrder::class,
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\WordpressPageController::class => Factory\Controller\WordpressPageControllerFactory::class,
            Controller\RedirectExternalJobs::class => InvokableFactory::class,
            Controller\CreateSingleJob::class => Factory\Controller\CreateSingleJobFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CreateSingleJob::class => Factory\Controller\Plugin\CreateSingleJobFactory::class,
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
            View\Helper\JobboardApplyUrl::class => Factory\View\Helper\JobboardApplyUrlFactory::class,
        ],
        'aliases' => [
            'wordpress' => WordpressApi\View\Helper\WordpressContent::class,
            'landingpages' => View\Helper\LandingpagesList::class,
            'gastroApplyUrl' => View\Helper\JobboardApplyUrl::class,
        ],
        'delegators' => [
            'jobUrl' => [
                Factory\View\Helper\JobUrlDelegatorFactory::class,
            ],
        ],
    ],

    'view_manager' => [
         'template_map' => [
             'layout/layout' => __DIR__ . '/../view/layout.phtml',
             'core/index/index' => __DIR__ . '/../view/index.phtml',
             'piwik' => __DIR__ . '/../view/piwik.phtml',
             'footer' => __DIR__ . '/../view/footer.phtml',
             'footer-application' => __DIR__ . '/../view/footer-application.phtml',
             'jobs/jobboard/index.ajax.phtml' => __DIR__ . '/../view/jobs/index.ajax.phtml',
             'jobs/jobboard/index' => __DIR__ . '/../view/jobs/index.phtml',
             'jobs/manage/approval' => __DIR__ . '/../view/jobs/approval.phtml',
             'main-navigation' => __DIR__ . '/../view/main-navigation.phtml',
             'auth/index/login-info' => __DIR__ . '/../view/login-info.phtml',
             'gastro24/wordpress-page/index' => __DIR__ . '/../view/gastro24/wordpress-page/index.phtml',
             'gastro24/wordpress-page/index.ajax' => __DIR__ . '/../view/gastro24/wordpress-page/index.ajax.phtml',
             'content/regionen' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'content/staedte' => __DIR__ . '/../view/gastro24/content/index.phtml',
             'jobs-by-mail/mail/jobs' => __DIR__ . '/../view/mail/jobs.phtml',
             'jobs-by-mail/mail/confirmation' => __DIR__ . '/../view/mail/confirmation.phtml',
             'jobs-by-mail/mail/confirmation.en' => __DIR__ . '/../view/mail/confirmation.en.phtml',
             'mail/header' => __DIR__ . '/../view/mail/header.phtml',
             'mail/footer' => __DIR__ . '/../view/mail/footer.phtml',
             'mail/footer.en' => __DIR__ . '/../view/mail/footer.en.phtml',
             'mail/forgotPassword' =>  __DIR__ . '/../view/mail/forgot-password.phtml',
             'mail/forgotPassword.en' =>  __DIR__ . '/../view/mail/forgot-password.en.phtml',
             'mail/register' =>  __DIR__ . '/../view/mail/register.phtml',
             'mail/register.en' =>  __DIR__ . '/../view/mail/register.en.phtml',
             'mail/job-accepted.en' => __DIR__ . '/../view/mail/job-accepted.en.phtml',
             'mail/job-accepted' => __DIR__ . '/../view/mail/job-accepted.phtml',
             'mail/job-created.en' => __DIR__ . '/../view/mail/job-created.en.phtml',
             'mail/job-created' => __DIR__ . '/../view/mail/job-created.phtml',
             'mail/job-pending.en' => __DIR__ . '/../view/mail/job-pending.en.phtml',
             'mail/job-pending' => __DIR__ . '/../view/mail/job-pending.phtml',
             'mail/job-rejected.en' => __DIR__ . '/../view/mail/job-rejected.en.phtml',
             'mail/job-rejected' => __DIR__ . '/../view/mail/job-rejected.phtml',
             'startpage'  => __DIR__ . '/../view/startpage.phtml',
             'templates/default/index' => __DIR__ . '/../view/templates/default/index.phtml',
             'templates/classic/index' => __DIR__ . '/../view/templates/classic/index.phtml',
             'templates/modern/index' => __DIR__ . '/../view/templates/modern/index.phtml',
             'iframe/iFrame.phtml' => __DIR__ . '/../view/jobs/iframe/iFrame.phtml',
             'gastro24/jobs/view-extern' => __DIR__ . '/../view/jobs/view-extern.phtml',
             'gastro24/create-single-job/index' => __DIR__ . '/../view/jobs/create-single-job.phtml',
             'gastro24/form/create-single-job' => __DIR__ . '/../view/jobs/create-single-job-form.phtml',
             'layout/application-form' => __DIR__ . '/../view/layout-application-form.phtml',
             'contactform.view' => __DIR__ . '/../view/contactform.phtml',
             'gastro24/jobs/user-product-info' => __DIR__ . '/../view/jobs/user-product-info.phtml',
             'pagination-control' => __DIR__ . '/../view/pagination-control.phtml',
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
            'Jobs/PreviewFieldset' => Form\JobPreviewFieldsetDelegator::class,
        ],
        'factories' => [
            Form\CreateSingleJobForm::class => InvokableFactory::class,
            Form\UserProductInfo::class => InvokableFactory::class,
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
                        'child_routes' => [
                            'view' => [
                                'type' => 'regex',
                                'options' => [
                                    'regex' => '-(?<id>[a-f0-9]+)\.html',
                                    'spec' => '-%id%.html',
                                    'route' => null,
                                ],
                            ],
                            'view-extern' => [
                                'type' => 'regex',
                                'options' => [
                                    'regex' => '-x(?<id>[a-f0-9]+)\.html',
                                    'spec' => '-x%id%.html',
                                    'route' => null,
                                    'defaults' => [
                                        'controller' => Controller\RedirectExternalJobs::class,
                                        'action' => 'index',
                                    ],
                                ],
                            ],
                            'single' => [
                                'type' => 'Literal',
                                'options' => [
                                    'route' => '/single',
                                    'defaults' => [
                                        'controller' => Controller\CreateSingleJob::class,
                                        'action' => 'index',
                                    ],
                                    'may_terminate' => true,
                                ],
                            ],
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

        'Auth/Events' => [ 'listeners' => [
            Listener\UserRegisteredListener::class => [ \Auth\Listener\Events\AuthEvent::EVENT_USER_REGISTERED, true ],
        ]],

        'Jobs/JobContainer/Events' => [ 'listeners' => [
            Listener\ValidateUserProduct::class => [ 'ValidateJob', true ],
            Listener\InjectUserProductInfo::class => [ \Core\Form\Event\FormEvent::EVENT_INIT, true ],
        ]],

        'Jobs/Events' => [ 'listeners' => [
            Listener\IncreaseJobCount::class => [ JobEvent::EVENT_JOB_CREATED, true ],
        ]],
    ],
];
