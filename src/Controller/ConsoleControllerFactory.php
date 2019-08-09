<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Controller;

use Interop\Container\ContainerInterface;
use Stellenmarkt\Options\CompanyTemplatesMap;

/**
 * Factory for \Stellenmarkt\Controller\ConsoleController
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ConsoleControllerFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): ConsoleController {
        $map = $container->get('Config');
        $map = $map['options'][CompanyTemplatesMap::class][0]['map'] ?? [];

        return new ConsoleController(
            $container->get('repositories')->get('Organizations'),
            $map
        );
    }
}
