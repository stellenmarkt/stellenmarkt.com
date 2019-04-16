<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Filter;

use Interop\Container\ContainerInterface;
use Zend\ServiceManager\Factory\FactoryInterface;

/**
 * Factory for \Stellenmarkt\Filter\PdfFileUri
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class PdfFileUriFactory implements FactoryInterface
{
    
    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {

        $helpers = $container->get('ViewHelperManager');
        $serverUrl = $helpers->get('serverUrl');
        $basepath = $helpers->get('basepath');
        $path = $serverUrl($basepath()) . '/';
        $service = new PdfFileUri($path);

        return $service;    
    }
}
