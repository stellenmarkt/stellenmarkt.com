<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\WordpressApi\Factory\Listener;

use Gastro24\WordpressApi\Listener\WordpressContentSnippet;
use Gastro24\WordpressApi\Options\WordpressContentSnippetOptions;
use Gastro24\WordpressApi\Service\WordpressClient;
use Interop\Container\ContainerInterface;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class WordpressContentSnippetFactory implements FactoryInterface
{

    public function __invoke(ContainerInterface $container, $requestedName, array $options = null)
    {
        $client = $container->get(WordpressClient::class);
        $options = $container->get(WordpressContentSnippetOptions::class);
        $listener = new WordpressContentSnippet($client);
        $listener->setIdMap($options->getIdMap());

        return $listener;
    }

    /**
     * Create service
     *
     * @param ServiceLocatorInterface $serviceLocator
     *
     * @return mixed
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        return $this($serviceLocator, WordpressContentSnippet::class);
    }
}