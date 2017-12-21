<?php

namespace Gastro24;

use Core\ModuleManager\ModuleConfigLoader;
use Gastro24\Options\Landingpages;
use Zend\Console\Console;
use Zend\Mvc\MvcEvent;
use Zend\Stdlib\Parameters;

/**
 * Bootstrap class of our demo skin
 */
class Module
{
    const TEXT_DOMAIN = __NAMESPACE__;

    /**
     * indicates, that the autoload configuration for this module should be loaded.
     * @see
     *
     * @var bool
     */
    public static $isLoaded=false;

    /**
     * Tells the autoloader, where to search for the Gastro24 classes
     *
     * @return array
     */
    public function getAutoloaderConfig()
    {

        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src',
                ),
            ),
        );
    }

    /**
     * Using the ModuleConfigLoader allow you to split the modules.config.php into several files.
     *
     * @return array
     */
    public function getConfig()
    {
        return ModuleConfigLoader::load(__DIR__ . '/config');
    }

    function onBootstrap(MvcEvent $e)
    {
        self::$isLoaded=true;
        $eventManager = $e->getApplication()->getEventManager();
        $services     = $e->getApplication()->getServiceManager();

        /*
         * remove Submenu from "applications"
         */
        $config=$services->get('config');
        unset($config['navigation']['default']['apply']['pages']);
        $services->setAllowOverride(true);
        $services->setService('config', $config);
        $services->setAllowOverride(false);

        if (!Console::isConsole()) {
            $sharedManager = $eventManager->getSharedManager();

            /*
             * use a neutral layout, when rendering the application form and its result page.
             * Also the application preview should be rendered in this layout.
             *
             * We need a post dispatch hook on the controller here as we need to have
             * the application entity to determine how to set the layout in the preview page.
             */
            $listener = function ($event) {
	            $viewModel  = $event->getViewModel();
	            $template   = 'layout/application-form';
	            $controller = $event->getTarget();
	
	            if ($controller instanceof \Applications\Controller\ApplyController) {
		            $viewModel->setTemplate($template);
		            return;
	            }
	
	            if ($controller instanceof \Applications\Controller\ManageController
	                && 'detail' == $event->getRouteMatch()->getParam('action')
	                && 200 == $event->getResponse()->getStatusCode()
	            ) {
		            $result = $event->getResult();
		            if (!is_array($result)) {
			            $result = $result->getVariables();
		            }
		            if ($result['application']->isDraft()) {
			            $viewModel->setTemplate($template);
		            }
	            }
	
            };
            
            $sharedManager->attach(
                'Applications',
                MvcEvent::EVENT_DISPATCH,$listener,
                -2 /*postDispatch, but before most of the other zf2 listener*/
            );
            $sharedManager->attach(
            	'CamMediaintown',
	            MvcEvent::EVENT_DISPATCH,$listener,
	            -2);

            $eventManager->attach(MvcEvent::EVENT_ROUTE, function(MvcEvent $event) {
                $routeMatch = $event->getRouteMatch();

                if (!$routeMatch) { return; }

                if ('lang/landingPage' == $routeMatch->getMatchedRouteName()) {
                    $services = $event->getApplication()->getServiceManager();
                    $options = $services->get(Landingpages::class);
                    $term = $routeMatch->getParam('q');

                    if (!$term) {
                        return;
                    }

                    $query = $options->getQueryParameters($term);
                    $routeMatch->setParam('wpId', $options->getIdMap($term));
                    $routeMatch->setParam('isLandingPage', true);

                    if ($query) {
                        $event->getRequest()->setQuery(new Parameters($query));
                    }

                    return;
                }

                if ('lang/jobboard' == $routeMatch->getMatchedRouteName()) {
                    $services = $event->getApplication()->getServiceManager();
                    $options = $services->get(Landingpages::class);
                    $query = $event->getRequest()->getQuery();

                    foreach ([
                        'r' => '__region_MultiString',
                        'c' => '__organizationTag',
                        'i' => '__industry_MultiString',
                        't' => '__employmentType_MultiString',
                        ] as $shortName => $longName) {

                        if ($v = $query->get($shortName)) {
                            $query->set($longName, $v);
                            $query->offsetUnset($shortName);
                        }
                    }
                    $query = $query->toArray();
                    unset($query['clear']);
                    if (isset($query['q'])) {
                        $query['q'] = strtolower($query['q']);
                    }
                    $map = $options->getQueryMap();

                    foreach ($map as $term => $spec) {
                        if (isset($spec['q'])) { $spec['q'] = strtolower($spec['q']); }
                        if ($spec === $query) {
                            /* \Zend\Http\PhpEnvironment\Response $response */
                            $url = $event->getRouter()->assemble(['q' => $term, 'format' => 'html'], ['name' => 'lang/landingPage']);
                            $response = $event->getResponse();
                            $response->getHeaders()->addHeaderLine('Location', $url);
                            $response->setStatusCode(302);
                            $event->setResult($response);
                            return $response;
                        }
                    }

                }

            }, -9999);
        }

    }
}
