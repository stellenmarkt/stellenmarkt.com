<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\WordpressApi\Factory\Listener;

use Stellenmarkt\WordpressApi\Filter\PageIdMap;
use Stellenmarkt\WordpressApi\Listener\WordpressContentSnippet;
use Stellenmarkt\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test 
 */
class WordpressContentSnippetFactory implements FactoryInterface
{
	
	/**
	 * @param ContainerInterface $container
	 * @param string $requestedName
	 * @param array|null $options
	 *
	 * @return WordpressContentSnippet
	 */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client   = $container->get(WordpressClient::class);
        $idMap    = $container->get('FilterManager')->get(PageIdMap::class);
        $listener = new WordpressContentSnippet($client);

        $listener->setIdMap($idMap);

        return $listener;
    }
}