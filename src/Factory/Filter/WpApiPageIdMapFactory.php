<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Factory\Filter;

use Stellenmarkt\Options\Landingpages;
use Stellenmarkt\WordpressApi\Filter\PageIdMap;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @author Anthonius Munthi <me@itstoni.com>
 * @todo write test 
 */
class WpApiPageIdMapFactory implements FactoryInterface
{

    /**
     * Creates a PageIdMap instance.
     *
     * @param ContainerInterface $container
     * @param string             $requestedName
     * @param array|null         $options
     *
     * @return PageIdMap
     */
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $options = $container->get(Landingpages::class);
        $map     = $options->getIdMap();
        $filter  = new PageIdMap($map);

        return $filter;
    }
}
