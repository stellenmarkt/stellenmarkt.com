<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Factory\View\Helper;

use Gastro24\View\Helper\LandingpagesList;
use Gastro24\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface; 

/**
 * Factory for \Gastro24\View\Helper\LandingpagesList
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class LandingpagesListFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client  = $container->get(WordpressClient::class);
        $options = $container->get('Gastro24/WordpressApiOptions');
        $idMap   = $options->getIdMap();

        return new LandingpagesList($client, $idMap);
    }
    
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        /* @var \Zend\ServiceManager\AbstractPluginManager $serviceLocator */
        return $this($serviceLocator->getServiceLocator(), LandingpagesList::class);
    }
}
