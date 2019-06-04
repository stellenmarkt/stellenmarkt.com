<?php
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Stellenmarkt\Factory\Delegator;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class SitemapOptionsDelegator implements DelegatorFactoryInterface
{
    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $name,
        callable $callback,
        ?array $options = null
    ) {
        $options = $callback();
        $options->setLanguages(['de']);

        return $options;
    }
}
