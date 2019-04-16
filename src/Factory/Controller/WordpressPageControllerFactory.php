<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Factory\Controller;

use Stellenmarkt\Controller\WordpressPageController;
use Stellenmarkt\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Stellenmarkt\Controller\WordpressPageController
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test  
 */
class WordpressPageControllerFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client = $container->get(WordpressClient::class);
        $client = $client->plugin('wp');

        return new WordpressPageController($client);
    }
}
