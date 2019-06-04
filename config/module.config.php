<?php
namespace Stellenmarkt;

use Stellenmarkt\Form\JobDetailsHydrator;
use Stellenmarkt\Form\JobDetailsHydratorFactory;
use Stellenmarkt\Options\Landingpages;
use Jobs\Listener\Events\JobEvent;
use SimpleImport\Entity\Crawler;
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

    'simple_import_crawler_processor_manager' => [
        'factories' => [
            Crawler::TYPE_JOB => SimpleImport\JobProcessorFactory::class
        ]
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
            'gastroApplyUrl' => View\Helper\JobboardApplyUrl::class,
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
             'stellenmarkt/jobs/view-mr-datentechnik' => __DIR__ . '/../view/jobs/inline/mr-datentechnik.phtml',
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
            'Stellenmarkt/JobPdfUpload' => Form\JobPdfFactory::class
        ],
        'aliases' => [
            'Orders/InvoiceAddressSettingsFieldset' => Form\InvoiceAddressSettingsFieldset::class,
        ]
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
        Options\CompanyTemplatesMap::class => [[
            /* organizationId => View-Template-Name */
            'map' => [
                '5cd94e820fc61f76a506cf9c' => 'stellenmarkt/jobs/view-ics', // icsag
                '5b84e64900c05016061616a2' => 'stellenmarkt/jobs/view-default', // transporeon
                '5c98fbd20fc61f60d7574e62' => 'stellenmarkt/jobs/view-default', // arwa
                '5c5aad0f0fc61f78f770bcb2' => 'stellenmarkt/jobs/view-default', // persona
                '5c5aac860fc61f78f96cf2b3' => 'stellenmarkt/jobs/view-default', // orizon
                '5c6d5c640fc61f6eeb1c0ca2' => 'stellenmarkt/jobs/view-default', // rosinke
                '5c50665600c050c815ee91b6' => 'stellenmarkt/jobs/view-default', // universaljob
                '5bd3261f00c050db5fdfe212' => 'stellenmarkt/jobs/view-default', // swisselect
                '5c0e7ea400c0505160053488' => 'stellenmarkt/jobs/view-default', // careforce
                '5c2d1ea000c0508439ec4126' => 'stellenmarkt/jobs/view-default',
                '5c8fc00b0fc61f2bed39f368' => 'stellenmarkt/jobs/view-default',
                '5ca5fb9d0fc61f715d42f0f4' => 'stellenmarkt/jobs/view-default',
                '5c2d0bc400c050212bec4122' => 'stellenmarkt/jobs/view-default',
                '5c41f5ae00c050df320d5dbb' => 'stellenmarkt/jobs/view-default',
                '5cab2a2f0fc61f499c431022' => 'stellenmarkt/jobs/view-default',
                '5c2d2a0d00c050463dec411e' => 'stellenmarkt/jobs/view-default',
                '5c055c1800c050242ca5ac48' => 'stellenmarkt/jobs/view-default',
                '5c9b98570fc61f228a39ffb8' => 'stellenmarkt/jobs/view-default',
                '5ca753910fc61f25c9022cb2' => 'stellenmarkt/jobs/view-default',
                '5ce533550fc61f78984790d4' => 'stellenmarkt/jobs/view-default',
                '5c769dba0fc61f53c1499002' => 'stellenmarkt/jobs/view-default',
                '5be5b68e00c050193e5676af' => 'stellenmarkt/jobs/view-default',
                '5c1e14bd00c050f1178868fd' => 'stellenmarkt/jobs/view-default',
                '5cb5e5bf0fc61f660c5bcfa2' => 'stellenmarkt/jobs/view-default',
                '5c2d2f5700c050e63cec4122' => 'stellenmarkt/jobs/view-default',
                '5c8fc1800fc61f71d5577a9e' => 'stellenmarkt/jobs/view-default',
                '5c7517850fc61f705f74f802' => 'stellenmarkt/jobs/view-default',
                '5c9517420fc61f76790875b4' => 'stellenmarkt/jobs/view-default',
                '5c6d673c0fc61f22074d3e2e' => 'stellenmarkt/jobs/view-default',
                '5c2d0e3d00c0507d25ec4126' => 'stellenmarkt/jobs/view-default',
                '5c8f79b40fc61f0fbb1b4bb2' => 'stellenmarkt/jobs/view-default',
                '5c61662a0fc61f061b0a3452' => 'stellenmarkt/jobs/view-default',
                '5cdc299d0fc61f1dd55599e2' => 'stellenmarkt/jobs/view-default',
                '5c40a78a00c0502313cde072' => 'stellenmarkt/jobs/view-default',
                '5c58658300c0504511e33f72' => 'stellenmarkt/jobs/view-default',
                '5c66dc8f0fc61f242a100278' => 'stellenmarkt/jobs/view-default',
                '5c640c2b0fc61f3c8145b497' => 'stellenmarkt/jobs/view-default',
                '5c66e21e0fc61f3915551a26' => 'stellenmarkt/jobs/view-default',
                '5c94ec240fc61f6c191db2d2' => 'stellenmarkt/jobs/view-default',
                '5c2d166000c0507d25ec412c' => 'stellenmarkt/jobs/view-default',
                '5bd3261f00c050db5fdfe212' => 'stellenmarkt/jobs/view-default',
                '5ca5ff720fc61f6f4b4c3742' => 'stellenmarkt/jobs/view-default',
                '5c30bce600c050240c61c8d3' => 'stellenmarkt/jobs/view-default',
                '5c2e241700c050204b863de0' => 'stellenmarkt/jobs/view-default',
                '5c2d283500c050333cec4122' => 'stellenmarkt/jobs/view-default',
                '5c94bbfe0fc61f480f67e9e2' => 'stellenmarkt/jobs/view-default',
                '5c2ceb7f00c0507e25ec411e' => 'stellenmarkt/jobs/view-default',
                '5c5c62df0fc61f13db4777fc' => 'stellenmarkt/jobs/view-default',
                '5c055d7000c050242ca5ac4a' => 'stellenmarkt/jobs/view-default',
                '5c2d26ad00c050583bec4126' => 'stellenmarkt/jobs/view-default',
                '5c2d1f7900c0503f3aec4126' => 'stellenmarkt/jobs/view-default',
                '5c1a6b0400c050d11d7fabf0' => 'stellenmarkt/jobs/view-default',
                '5c8a54380fc61f30cb34cc12' => 'stellenmarkt/jobs/view-default',
                '5c6d64de0fc61f6eeb1c0caa' => 'stellenmarkt/jobs/view-default',
                '5c866a490fc61f1f6608bd12' => 'stellenmarkt/jobs/view-default',
                '5c01521100c050b14801842e' => 'stellenmarkt/jobs/view-default',
                '5c6ad4ee0fc61f62dd3c8732' => 'stellenmarkt/jobs/view-default',
                '5bbf68d100c050f14d975412' => 'stellenmarkt/jobs/view-default',
                '5bfa8da800c050243f91156f' => 'stellenmarkt/jobs/view-default',
                '5c7d12a90fc61f7ec13f68b6' => 'stellenmarkt/jobs/view-default',
                '5bdc5e5100c0506e7bb5893b' => 'stellenmarkt/jobs/view-default',
                '5c3f4f6c00c0500550f79207' => 'stellenmarkt/jobs/view-default',
                '5c1ce51000c0506721334b10' => 'stellenmarkt/jobs/view-default',
                '5c41e76b00c050e43c0d5dbb' => 'stellenmarkt/jobs/view-default',
                '5ca4a3060fc61f4eb8733992' => 'stellenmarkt/jobs/view-default',
                '5c9cac850fc61f39095ec532' => 'stellenmarkt/jobs/view-default',
                '5c50665600c050c815ee91b6' => 'stellenmarkt/jobs/view-default',
                '5c9b76d10fc61f03a611a15a' => 'stellenmarkt/jobs/view-default',
                '5c2d278d00c0508e39ec4122' => 'stellenmarkt/jobs/view-default',
                '5c13de0c00c050e707456485' => 'stellenmarkt/jobs/view-default',
                '5c06ba2100c0508850cc96ee' => 'stellenmarkt/jobs/view-default',
                '5c52ebac00c0502f64cff45b' => 'stellenmarkt/jobs/view-default',
                '5c1e4d9000c0502c118868fd' => 'stellenmarkt/jobs/view-default',
                '5ca765430fc61f25b7523938' => 'stellenmarkt/jobs/view-default',
                '5c0e806200c050d65f053488' => 'stellenmarkt/jobs/view-default',
                '5be5b78000c050e6315676b3' => 'stellenmarkt/jobs/view-default',
                '5bf56f3400c0501f7ff9a3d2' => 'stellenmarkt/jobs/view-default',
                '5ca5d5e40fc61f5a2802aa82' => 'stellenmarkt/jobs/view-default',
                '5bdaed3700c050fd53626303' => 'stellenmarkt/jobs/view-default',
                '5cb5ada50fc61f2f9e33a882' => 'stellenmarkt/jobs/view-default',
                '5c2d254100c0503f3aec4128' => 'stellenmarkt/jobs/view-default',
                '5c5867f000c0508310e33f7e' => 'stellenmarkt/jobs/view-default',
                '5c2d126500c0502039ec4122' => 'stellenmarkt/jobs/view-default',
                '5c2d0d2100c0508125ec4122' => 'stellenmarkt/jobs/view-default',
                '5c640bab0fc61f3c9b1b4472' => 'stellenmarkt/jobs/view-default',
                '5c98fbd20fc61f60d7574e62' => 'stellenmarkt/jobs/view-default',
                '5c7927cf0fc61f10c2414e44' => 'stellenmarkt/jobs/view-default',
                '5c2e493b00c050fa4f863de0' => 'stellenmarkt/jobs/view-default',
                '5bdc3a0600c050d37cb5893b' => 'stellenmarkt/jobs/view-default',
                '5c2d183700c0504439ec4126' => 'stellenmarkt/jobs/view-default',
                '5c2d0f0900c0504135ec4122' => 'stellenmarkt/jobs/view-default',
                '5cded8c90fc61f2b4d1310f2' => 'stellenmarkt/jobs/view-default',
                '5c9521fb0fc61f4d62143592' => 'stellenmarkt/jobs/view-default',
                '5bfb103000c0507a47911571' => 'stellenmarkt/jobs/view-default',
                '5cbed6710fc61f3d06269732' => 'stellenmarkt/jobs/view-default',
                '5ccc41680fc61f719d4c90e2' => 'stellenmarkt/jobs/view-default',
                '5c2d175300c0504135ec4124' => 'stellenmarkt/jobs/view-default',
                '5c66de500fc61f248b4b6f14' => 'stellenmarkt/jobs/view-default',
                '5c2d2ad300c050333cec4124' => 'stellenmarkt/jobs/view-default',
                '5ccc23690fc61f5eee2b6dc2' => 'stellenmarkt/jobs/view-default',
                '5c9b97630fc61f2487567392' => 'stellenmarkt/jobs/view-default',
                '5c88ea4d0fc61f216a626302' => 'stellenmarkt/jobs/view-default',
                '5c7d103c0fc61f58d90ea9d2' => 'stellenmarkt/jobs/view-default',
                '5ca7323b0fc61f06811e9472' => 'stellenmarkt/jobs/view-default',
                '5c3df52700c050482361f0aa' => 'stellenmarkt/jobs/view-default',
                '5cd97a8b0fc61f44c07ab672' => 'stellenmarkt/jobs/view-default',
                '5c2d1c8400c0504439ec4128' => 'stellenmarkt/jobs/view-default',
                '5c2d0a5f00c0506b35ec411e' => 'stellenmarkt/jobs/view-default',
                '5ce7d9310fc61f4177235f48' => 'stellenmarkt/jobs/view-default',
                '5c640c010fc61f4fb531ffa2' => 'stellenmarkt/jobs/view-default',
                '5c0e93d200c050dc61053488' => 'stellenmarkt/jobs/view-default',
                '5c51d53c00c050fd4ffc3f6b' => 'stellenmarkt/jobs/view-default',
                '5cd2d1b90fc61f1add10faf2' => 'stellenmarkt/jobs/view-default',
                '5c083f8000c0506175d327e4' => 'stellenmarkt/jobs/view-default',
                '5cb0a8cf0fc61f59042a5a12' => 'stellenmarkt/jobs/view-default',
                '5c0fc88800c0500114923d4b' => 'stellenmarkt/jobs/view-default',
                '5bfe7ba700c0504343be0769' => 'stellenmarkt/jobs/view-default',
                '5c3f2afb00c0500550f79203' => 'stellenmarkt/jobs/view-default',
                '5c484fcc00c050f628065a74' => 'stellenmarkt/jobs/view-default',
                '5c8bda810fc61f4a4b7e9802' => 'stellenmarkt/jobs/view-default',
                '5cd95def0fc61f179b020f06' => 'stellenmarkt/jobs/view-default',
                '5c90beee0fc61f2ac810c842' => 'stellenmarkt/jobs/view-default',
                '5cc057450fc61f0dde321052' => 'stellenmarkt/jobs/view-mr-datentechnik', // mr-datentechnik
                '5c2d261300c0500a3bec4128' => 'stellenmarkt/jobs/view-default',
                '5bdaeb6300c050a154626303' => 'stellenmarkt/jobs/view-default',
                '5c768b250fc61f4122727948' => 'stellenmarkt/jobs/view-default',
                '5c1cd5d100c050282b334b10' => 'stellenmarkt/jobs/view-default',
                '5c7010b10fc61f33a337e882' => 'stellenmarkt/jobs/view-default',
                '5cc32a800fc61f1e1d6401f2' => 'stellenmarkt/jobs/view-default',
                '5cc6bab00fc61f2d551a09f2' => 'stellenmarkt/jobs/view-default',
                '5cb725150fc61f4925643902' => 'stellenmarkt/jobs/view-default',
                '5cd580f20fc61f0e5036f382' => 'stellenmarkt/jobs/view-default',
                '5c7ff8b70fc61f7a66030e14' => 'stellenmarkt/jobs/view-default',
                '5c8264290fc61f23761416b4' => 'stellenmarkt/jobs/view-default',
                '5ca4a8c90fc61f2478746396' => 'stellenmarkt/jobs/view-default',
                '5ce694360fc61f1c33559e4c' => 'stellenmarkt/jobs/view-default',
                '5bfb0fa400c0504d4191156d' => 'stellenmarkt/jobs/view-default',
                '5bfbd45000c05097690769a1' => 'stellenmarkt/jobs/view-default',
                '5cdc2a040fc61f1de75515e2' => 'stellenmarkt/jobs/view-default',
                '5c2d082b00c0507f25ec411e' => 'stellenmarkt/jobs/view-default',
                '5c66df220fc61f3af66e42e2' => 'stellenmarkt/jobs/view-default',
                '5c34ae2b00c050047a10bc73' => 'stellenmarkt/jobs/view-default',
                '5c7d141b0fc61f6d1f4e9bf2' => 'stellenmarkt/jobs/view-default',
                '5c8f81230fc61f71d5577a92' => 'stellenmarkt/jobs/view-default',
                '5c3f619900c0505052f79203' => 'stellenmarkt/jobs/view-default',
                '5c64462c0fc61f5ab86ea628' => 'stellenmarkt/jobs/view-default',
                '5c2d1d6700c0500a3bec411e' => 'stellenmarkt/jobs/view-default',
                '5ca36d270fc61f228d4171f4' => 'stellenmarkt/jobs/view-default',
                '5ce539c30fc61f1ce26cf6e2' => 'stellenmarkt/jobs/view-default',
                '5c9a3c5a0fc61f52b445e312' => 'stellenmarkt/jobs/view-default',
                '5c051b2f00c0509927a5ac48' => 'stellenmarkt/jobs/view-default',
                '5c18e74a00c05007750a8734' => 'stellenmarkt/jobs/view-default',
                '5c35d95300c050711c6772db' => 'stellenmarkt/jobs/view-default',
                '5b9f6afd00c0506b39423923' => 'stellenmarkt/jobs/view-default',
                '5cde9e230fc61f1fa7788482' => 'stellenmarkt/jobs/view-default',
                '5bbe0e3500c0509e19d24651' => 'stellenmarkt/jobs/view-default',
                '5bbf6c4100c050f94d975412' => 'stellenmarkt/jobs/view-default',
                '5bc5c2f000c050c1709b9baa' => 'stellenmarkt/jobs/view-default',
                '5cde9a2e0fc61f74004ac262' => 'stellenmarkt/jobs/view-default',
                '5bdaecb300c050a954626307' => 'stellenmarkt/jobs/view-default',
                '5ccc31500fc61f6893104762' => 'stellenmarkt/jobs/view-default',
                '5be1819c00c0505e1b6d65ae' => 'stellenmarkt/jobs/view-default',
                '5bdc3aba00c0505304b5893b' => 'stellenmarkt/jobs/view-default',
                '5bd2b1e100c0507a54dfe212' => 'stellenmarkt/jobs/view-default',
                '5be7484f00c0506e105941fc' => 'stellenmarkt/jobs/view-default',
                '5bf5555a00c0507108f9a3d2' => 'stellenmarkt/jobs/view-default',
                '5bf80e9e00c050c160e09679' => 'stellenmarkt/jobs/view-default',
                '5bfe95b100c0506b53be0761' => 'stellenmarkt/jobs/view-default',
                '5c055bba00c050ff39a5ac48' => 'stellenmarkt/jobs/view-default',
                '5c055ce200c050ff39a5ac4e' => 'stellenmarkt/jobs/view-default',
                '5c055c9700c050bb2aa5ac48' => 'stellenmarkt/jobs/view-default',
                '5c0285e200c0502422ef7111' => 'stellenmarkt/jobs/view-default',
                '5c5aad240fc61f7902577bd3' => 'stellenmarkt/jobs/view-default',
                '5c0928f700c050c725547140' => 'stellenmarkt/jobs/view-default',
                '5c388aa100c050ee6aa27b00' => 'stellenmarkt/jobs/view-default',
                '5c18e82000c050e6690a8734' => 'stellenmarkt/jobs/view-default',
                '5c3f2ddc00c0500550f79205' => 'stellenmarkt/jobs/view-default',
                '5c4865fa00c050c12e065a74' => 'stellenmarkt/jobs/view-default',
                '5c9e413c0fc61f666e5350b2' => 'stellenmarkt/jobs/view-default',
                '5c40697b00c050fe05cde06d' => 'stellenmarkt/jobs/view-default',
                '5c5c64ae0fc61f49fe6799b2' => 'stellenmarkt/jobs/view-default',
                '5c8f92db0fc61f71d5577a94' => 'stellenmarkt/jobs/view-default',
                '5c6ff4f90fc61f1d3e39aa02' => 'stellenmarkt/jobs/view-default',
                '5c6d63340fc61f701a5d8c38' => 'stellenmarkt/jobs/view-default',
                '5c6d6fdb0fc61f3a4d3217cc' => 'stellenmarkt/jobs/view-default',
                '5c7006fb0fc61f286f264012' => 'stellenmarkt/jobs/view-default',
                '5c6fff840fc61f26bf2c2262' => 'stellenmarkt/jobs/view-default',
                '5c79137f0fc61f7203681df2' => 'stellenmarkt/jobs/view-default',
                '5c7915230fc61f10b4290c82' => 'stellenmarkt/jobs/view-default',
                '5c7691c60fc61f3a2549dd02' => 'stellenmarkt/jobs/view-default',
                '5c792e580fc61f05f1747772' => 'stellenmarkt/jobs/view-default',
                '5c7916d90fc61f10d927b668' => 'stellenmarkt/jobs/view-default',
                '5c8241b80fc61f3fe17b55e2' => 'stellenmarkt/jobs/view-default',
                '5c8921800fc61f368a7f8d82' => 'stellenmarkt/jobs/view-default',
                '5c890e9d0fc61f222a1a78b6' => 'stellenmarkt/jobs/view-default',
                '5c8f96b90fc61f1b8d5cca32' => 'stellenmarkt/jobs/view-default',
                '5c94dac90fc61f76790875b2' => 'stellenmarkt/jobs/view-default',
                '5c98d2c10fc61f4a9d587372' => 'stellenmarkt/jobs/view-default',
                '5c98af160fc61f4f44094b82' => 'stellenmarkt/jobs/view-default',
                '5ca5ea1b0fc61f71815150e2' => 'stellenmarkt/jobs/view-default',
                '5ca60fad0fc61f7fc81804a2' => 'stellenmarkt/jobs/view-default',
                '5ca364aa0fc61f455341eb28' => 'stellenmarkt/jobs/view-default',
                '5cab1c670fc61f23c7503142' => 'stellenmarkt/jobs/view-default',
                '5cb06db80fc61f39c50c1ad6' => 'stellenmarkt/jobs/view-default',
                '5cbf04ac0fc61f1585247622' => 'stellenmarkt/jobs/view-default',
                '5cd2ba9e0fc61f19376f6ad2' => 'stellenmarkt/jobs/view-default',
                '5cd94e820fc61f76a506cf9c' => 'stellenmarkt/jobs/view-default',
                '5c37353800c0502b4019463f' => 'stellenmarkt/jobs/view-default',
                '5c1c059900c050d3037b23c6' => 'stellenmarkt/jobs/view-default',
                '5c5af7950fc61f1a941542b2' => 'stellenmarkt/jobs/view-default',
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
