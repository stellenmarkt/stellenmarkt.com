<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Factory\View\Helper;

use Gastro24\WordpressApi\Filter\PageIdMap;
use Gastro24\WordpressApi\Service\WordpressClient;
use Gastro24\WordpressApi\View\Helper\WordpressContent;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\AbstractPluginManager;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressContentFactory implements FactoryInterface
{
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client = $container->get(WordpressClient::class);
        $idMap  = $container->get('filtermanager')->get(PageIdMap::class);

        $helper = new WordpressContent($client, $idMap);

        return $helper;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface|AbstractPluginManager $serviceLocator
     *
     * @return WordpressContent
     * @deprecated obsolete with ZF3
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator->getServiceLocator(), WordpressContent::class);
    }
}