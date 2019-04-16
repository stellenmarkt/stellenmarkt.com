<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Factory\View\Helper;

use Stellenmarkt\View\Helper\JobboardApplyUrl;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Stellenmarkt\View\Helper\JobboardApplyUrl
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class JobboardApplyUrlFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $urlHelper = $container->get('ViewHelperManager')->get('url');
        $helper    = new JobboardApplyUrl($urlHelper);

        return $helper;
    }
}
