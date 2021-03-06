<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Factory\View\Helper;

use Stellenmarkt\View\Helper\LandingpagesList;
use Stellenmarkt\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Stellenmarkt\View\Helper\LandingpagesList
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test  
 */
class LandingpagesListFactory implements FactoryInterface
{
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 *
	 * @return LandingpagesList
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client  = $container->get(WordpressClient::class);
        $options = $container->get('Stellenmarkt/WordpressApiOptions');
        $idMap   = $options->getIdMap();

        return new LandingpagesList($client, $idMap);
    }
}
