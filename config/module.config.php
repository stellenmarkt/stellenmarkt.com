<?php
namespace Stellenmarkt;

use Stellenmarkt\Form\JobDetailsHydrator;
use Stellenmarkt\Form\JobDetailsHydratorFactory;
use Stellenmarkt\Options\Landingpages;
use Jobs\Listener\Events\JobEvent;
use Zend\ServiceManager\Factory\InvokableFactory;

Module::$isLoaded = true;

/**
 * create a config/autoload/Stellenmarkt.global.php and put modifications there
 */

return [

    'doctrine' => [
        'driver' => [
            'odm_default' => [
                'drivers' => [
                    'Stellenmarkt\Entity' => 'annotation',
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

        'eventmanager' => [
            'odm_default' => [
                'subscribers' => [
                    Repository\Events\InjectJobSnapshotHydratorSubscriber::class,
                ],
            ],
        ],
    ],

    'Stellenmarkt' => [
        'dashboard' => [
            'enabled' => true,
            'widgets' => [
                'productInfo' => [
                    'script' => 'stellenmarkt/dashboard',
                ],
            ],
        ],
    ],

    'service_manager' => [
        'factories' => [
            'Auth/Dependency/Manager' => 'Stellenmarkt\Factory\Dependency\ManagerFactory',
            WordpressApi\Service\WordpressClient::class => WordpressApi\Factory\Service\WordpressClientFactory::class,
            WordpressApi\Listener\WordpressContentSnippet::class => WordpressApi\Factory\Listener\WordpressContentSnippetFactory::class,
            Listener\UserRegisteredListener::class => Listener\UserRegisteredListenerFactory::class,
            Listener\VoidListener::class => InvokableFactory::class,
            Listener\CreateJobOrder::class => Listener\CreateJobOrderFactory::class,
            Listener\SingleJobAcceptedListener::class => Listener\SingleJobAcceptedListenerFactory::class,
            Listener\JobDetailFileUpload::class => Listener\JobDetailFileUploadFactory::class,
            Listener\DeleteTemplateImage::class => Listener\DeleteTemplateImageFactory::class,
            Listener\AutoApproveChangedJobs::class => Listener\AutoApproveChangedJobsFactory::class,
        ],
        'aliases' => [
            'Orders\Form\Listener\InjectInvoiceAddressInJobContainer' => Listener\VoidListener::class,
            'Orders\Form\Listener\ValidateJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders\Form\Listener\DisableJobInvoiceAddress' => Listener\VoidListener::class,
            'Orders/Listener/BindInvoiceAddressEntity' => Listener\VoidListener::class,
            'Orders/Listener/CreateJobOrder' => Listener\CreateJobOrder::class,
        ],
        'delegators' => [
            \Sitemap\Event\FetchJobLinksListener::class => [
                Listener\FetchJobLinksForSitemapDelegatorFactory::class
            ],
            \Sitemap\Options\SitemapOptions::class => [
                Factory\Delegator\SitemapOptionsDelegator::class,
            ],
        ],
    ],

    'controllers' => [
        'factories' => [
            Controller\WordpressPageController::class => Factory\Controller\WordpressPageControllerFactory::class,
            Controller\RedirectExternalJobs::class => Controller\RedirectExternalJobsFactory::class,
            Controller\CreateSingleJob::class => Factory\Controller\CreateSingleJobFactory::class,
            Controller\ConsoleController::class => Controller\ConsoleControllerFactory::class,
        ],
    ],

    'controller_plugins' => [
        'factories' => [
            Controller\Plugin\CreateSingleJob::class => Factory\Controller\Plugin\CreateSingleJobFactory::class,
            //Controller\Plugin\Url::class => InvokableFactory::class,
        ],
//        'aliases' => [
//            'url' => Controller\Plugin\Url::class,
//            'Url' => Controller\Plugin\Url::class,
//        ],
    ],

    'filters' => [
        'factories' => [
            WordpressApi\Filter\PageIdMap::class => Factory\Filter\WpApiPageIdMapFactory::class,
            Filter\PdfFileUri::class => Filter\PdfFileUriFactory::class,
            Filter\OrganizationJobsListQuery::class => InvokableFactory::class,
        ],
        'aliases' => [
            'Organizations/ListJobQuery' => Filter\OrganizationJobsListQuery::class,
        ]
    ],

    'validators' => [
        'factories' => [
            Validator\IframeEmbeddableUri::class => InvokableFactory::class,
        ],
    ],

    'hydrators' => [
        'factories' => [
            JobDetailsHydrator::class => JobDetailsHydratorFactory::class,
        ],
    ],

    'view_helpers' => [
        'factories' => [
            WordpressApi\View\Helper\WordpressContent::class => WordpressApi\Factory\View\Helper\WordpressContentFactory::class,
          //  View\Helper\LandingpagesList::class => Factory\View\Helper\LandingpagesListFactory::class,
            View\Helper\JobboardApplyUrl::class => Factory\View\Helper\JobboardApplyUrlFactory::class,
            View\Helper\LogoUri::class => View\Helper\LogoUriFactory::class,
            View\Helper\OrgProfileUrl::class => InvokableFactory::class,
        ],
        'aliases' => [
            'wordpress' => WordpressApi\View\Helper\WordpressContent::class,
          // 'landingpages' => View\Helper\LandingpagesList::class,
            'jobboardApplyUrl' => View\Helper\JobboardApplyUrl::class,
            'gastroLogoUri' => View\Helper\LogoUri::class,
            'orgProfileUrl' => View\Helper\OrgProfileUrl::class,
        ],
        'delegators' => [
            'jobUrl' => [
                Factory\View\Helper\JobUrlDelegatorFactory::class,
            ],
        ],
    ],

    'view_helper_config' => [
        'headscript' => [
            'lang/jobs/manage' => [
                [\Zend\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("#sf-general-portalForm").hide(); }); })(jQuery);'],
            ],
            [\Zend\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("#jobs-list-filter").find("button[type=\'reset\']").text("X"); }); })(jQuery);'],
            'lang/applications/detail' => [
                [\Zend\View\Helper\HeadScript::SCRIPT, ';(function($) { $(function() { $("button[data-target=\'#cam-move-application\']").hide(); }); })(jQuery);'],
            ],
        ],
    ],

    'view_manager' => [
         'template_map' => [
             'error/404' => __DIR__ . '/../view/error/404.phtml',
             'error/403' => __DIR__ . '/../view/error/403.phtml',
             'error/index' => __DIR__ . '/../view/error/index.phtml',
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
             'stellenmarkt/wordpress-page/index' => __DIR__ . '/../view/stellenmarkt/wordpress-page/index.phtml',
             'stellenmarkt/wordpress-page/index.ajax' => __DIR__ . '/../view/stellenmarkt/wordpress-page/index.ajax.phtml',
             'content/regionen' => __DIR__ . '/../view/stellenmarkt/content/index.phtml',
             'content/staedte' => __DIR__ . '/../view/stellenmarkt/content/index.phtml',
             'jobs-by-mail/form/subscribe/form' => __DIR__ . '/../view/jobs-by-mail/form.phtml',
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
             'stellenmarkt/mail/single-job-created' => __DIR__ . '/../view/mail/single-job-created.phtml',
             'stellenmarkt/mail/single-job-pending' => __DIR__ . '/../view/mail/single-job-pending.phtml',
             'stellenmarkt/mail/single-job-accepted' => __DIR__ . '/../view/mail/single-job-accepted.phtml',
             'auth/mail/new-registration.en' => __DIR__ . '/../view/mail/new-registration.en.phtml',
             'auth/mail/new-registration' => __DIR__ . '/../view/mail/new-registration.phtml',
             'auth/mail/user-confirmed.en' => __DIR__ . '/../view/mail/user-confirmed.en.phtml',
             'auth/mail/user-confirmed' => __DIR__ . '/../view/mail/user-confirmed.phtml',
             'startpage'  => __DIR__ . '/../view/startpage.phtml',
             'templates/default/index' => __DIR__ . '/../view/templates/default/index.phtml',
             'templates/classic/index' => __DIR__ . '/../view/templates/classic/index.phtml',
             'templates/modern/index' => __DIR__ . '/../view/templates/modern/index.phtml',
             'iframe/iFrame.phtml' => __DIR__ . '/../view/jobs/iframe/iFrame.phtml',
             'stellenmarkt/jobs/view-extern' => __DIR__ . '/../view/jobs/view-extern.phtml',
             'stellenmarkt/jobs/view-default' => __DIR__ . '/../view/jobs/inline/default.phtml',
             'stellenmarkt/jobs/view-ics' => __DIR__ . '/../view/jobs/inline/ics.phtml',
             'stellenmarkt/jobs/view-hochtaunus-kliniken' => __DIR__ . '/../view/jobs/inline/hochtaunus-kliniken.phtml',
             'stellenmarkt/jobs/view-strip-links' => __DIR__ . '/../view/jobs/inline/strip-links.phtml',
             'stellenmarkt/jobs/view-mr-datentechnik' => __DIR__ . '/../view/jobs/inline/mr-datentechnik.phtml',
             'stellenmarkt/jobs/view-1a-aerztevermittlung' => __DIR__ . '/../view/jobs/inline/1a-aerztevermittlung.phtml',
             'stellenmarkt/jobs/view-hkw' => __DIR__ . '/../view/jobs/inline/hkw.phtml',
             'stellenmarkt/jobs/view-intern' => __DIR__ . '/../view/jobs/view-intern.phtml',
             'stellenmarkt/create-single-job/index' => __DIR__ . '/../view/jobs/create-single-job.phtml',
             'stellenmarkt/form/create-single-job' => __DIR__ . '/../view/jobs/create-single-job-form.phtml',
             'stellenmarkt/form/job-details-fieldset' => __DIR__ . '/../view/jobs/job-details-fieldset.phtml',
             'stellenmarkt/dashboard' => __DIR__ . '/../view/stellenmarkt/dashboard.phtml',
             'layout/application-form' => __DIR__ . '/../view/layout-application-form.phtml',
             'contactform.view' => __DIR__ . '/../view/contactform.phtml',
             'stellenmarkt/jobs/user-product-info' => __DIR__ . '/../view/jobs/user-product-info.phtml',
             'pagination-control' => __DIR__ . '/../view/pagination-control.phtml',
             'auth/index/index' => __DIR__ . '/../view/auth/index/index.phtml',
             'auth/password/index' => __DIR__ . '/../view/auth/password/index.phtml',
             'auth/forgot-password/index' => __DIR__ . '/../view/auth/forgot-password/index.phtml',
             'content/applications-privacy-policy' => __DIR__ . '/../view/application-disclaimer.phtml',
             'organizations/profile/detail' => __DIR__ . '/../view/organizations/profile-detail.phtml',
             'organizations/profile/detail.ajax' => __DIR__ . '/../view/organizations/profile-detail.ajax.phtml',
             'organizations/profile/index.ajax.phtml' => __DIR__ . '/../view/organizations/index.ajax.phtml',
             'organizations/profile/disabled' => __DIR__ . '/../view/organizations/profile-disabled.phtml',
             'organizations/mail/invite-employee.phtml' => __DIR__ . '/../view/mail/invite-employee.phtml',

         ],
    ],

    'translator'   => [
        'translation_file_patterns' => [
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.php',
                'text_domain' => \Stellenmarkt\Module::TEXT_DOMAIN,
            ],
            [
                'type'     => 'phparray',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s-override.php',
            ],
        ],
    ],
    'form_elements' => [
        'invokables' => [
            'Jobs/Description' => 'Stellenmarkt\Form\JobsDescription',
            'Jobs/PreviewFieldset' => Form\JobPreviewFieldsetDelegator::class,
        ],
        'factories' => [
            Form\CreateSingleJobForm::class => InvokableFactory::class,
            Form\UserProductInfo::class => InvokableFactory::class,
            Form\InvoiceAddressSettingsFieldset::class => \Settings\Form\Factory\SettingsFieldsetFactory::class,
            Form\JobDetails::class => Form\JobDetailsFactory::class,
            Form\JobDetailsForm::class => InvokableFactory::class,
            'Stellenmarkt/JobPdfUpload' => Form\JobPdfFactory::class,
            Form\OrganizationsLiquidDesignFieldset::class => Form\OrganizationsLiquidDesignFieldsetFactory::class,
            Form\OrganizationsLiquidDesignForm::class => InvokableFactory::class
        ],
        'aliases' => [
            'Orders/InvoiceAddressSettingsFieldset' => Form\InvoiceAddressSettingsFieldset::class,
        ],
        'delegators' => [
            \Organizations\Form\Organizations::class => [
                Form\OrganizationsFormDelegatorFactory::class
            ],
        ],
    ],

    'mails' => [
        'factories' => [
            'Stellenmarkt/SingleJobMail' => Mail\SingleJobMailFactory::class,
        ],
    ],

    'router' => [
        'routes' => [
            'lang' => [
                'options' => [
                    'defaults' => [
                        'controller' => 'Jobs/Jobboard', //Overwrites the route of the start Page
                        'action'     => 'index',
                    ]
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
                    'jobs' => [
                        'options' => [
                            'route' => '/job',
                        ],
                        'child_routes' => [
                            'view' => [
                                //'type' => 'regex',
                                'options' => [
                                  //  'regex' => '-(?<id>[a-f0-9]+)\.html',
                                  //  'spec' => '-%id%.html',
                                  //  'route' => null,
                                    'defaults' => [
                                        'controller' => Controller\RedirectExternalJobs::class,
                                        'action' => 'index',
                                        'isPreview' => true,
                                    ],
                                ],
                            ],
                            'view-extern' => [
                                'type' => 'regex',
                                'options' => [
                                    'regex' => '-(?<id>[a-f0-9]+)\.html',
                                    'spec' => '-%id%.html',
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
                    ],
                    'organizations-profiles' => [
                                'type' => 'Regex',
                                'options' => [
                                    'regex' => '/profile-(?<name>.*?)-(?<id>[a-f0-9]+)$',
                                    'spec' => '/profile-%name%-%id%',
                                    'route' => '/',
                                    'constraints' => [
                                        'id' => '\w+',
                                    ],
                                    'defaults' => [
                                        'action' => 'detail',
                                        'controller' => 'Organizations/Profile'
                                    ],
                                ],


                    ]
                ],
            ],
        ],
    ],

    'console' => [
        'router' => [
            'routes' => [
                'stellenmarkt-migrate-liquiddesign' => [
                    'options' => [
                        'route' => 'stellenmarkt migrate-ld',
                        'defaults' => [
                            'controller' => Controller\ConsoleController::class,
                            'action' => 'index',
                        ]
                    ]
                ],
            ],
        ],
    ],

    'options' => [

        'Stellenmarkt/WordpressApiOptions' => [
            'class' => WordpressApi\Options\WordpressApiOptions::class,
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
        \Sitemap\Options\SitemapOptions::class => [
            'baseUrl' => 'https://www.stellenmarkt.com/',
        ],
        Landingpages::class => [],
        Options\JobDetailsForm::class => [],

        Options\LiquidDesignTemplatesMap::class => [[
            'map' => [
                'default' => 'stellenmarkt/jobs/view-default',
                'ICS' => 'stellenmarkt/jobs/view-ics',
                'Hochtaunus-Kliniken' => 'stellenmarkt/jobs/view-hochtaunus-kliniken',
                'Strip Links' => 'stellenmarkt/jobs/view-strip-links',
                'MR Datentechnik' => 'stellenmarkt/jobs/view-mr-datentechnik',
                '1A Ärztevermittlung' => 'stellenmarkt/jobs/view-1a-aerztevermittlung',
                'HKW' => 'stellenmarkt/jobs/view-hkw',
            ],
        ]],
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
            Listener\SingleJobAcceptedListener::class => [ JobEvent::EVENT_JOB_ACCEPTED, true ],
            Listener\AutoApproveChangedJobs::class => [JobEvent::EVENT_STATUS_CHANGED, true],
        ]],

        'Core/Ajax/Events' => [ 'listeners' => [
            Listener\JobDetailFileUpload::class  => [
                'events' => ['jobdetailsupload', 'jobdetailsdelete' => 'deletePdfFile'],
                'lazy'   => true
            ],
        ]],

        'Core/File/Events' => [ 'listeners' => [
            Listener\DeleteTemplateImage::class => [ \Core\Listener\Events\FileEvent::EVENT_DELETE, true ]
        ]],
    ],
];
