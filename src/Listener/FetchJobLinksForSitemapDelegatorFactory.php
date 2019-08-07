<?php
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Stellenmarkt\Listener;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class FetchJobLinksForSitemapDelegatorFactory implements DelegatorFactoryInterface
{

    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $name,
        callable $callback,
        ?array $options = null
    ) {
        return new FetchJobLinksForSitemapListener(
            $container->get('repositories')->get('Jobs')
        );
    }
}
