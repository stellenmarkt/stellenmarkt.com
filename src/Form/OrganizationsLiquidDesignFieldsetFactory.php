<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Form;

use Interop\Container\ContainerInterface;
use Stellenmarkt\Options\LiquidDesignTemplatesMap;

/**
 * Factory for \Stellenmarkt\Form\OrganizationsLiquidDesignFieldset
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class OrganizationsLiquidDesignFieldsetFactory
{
    public function __invoke(
        ContainerInterface $container,
        ?string $requestedName = null,
        ?array $options = null
    ): OrganizationsLiquidDesignFieldset {
        $options = $container->get(LiquidDesignTemplatesMap::class);
        $options = [
            'selectValues' => $options->getMapAsSelectValues()
        ];

        return new OrganizationsLiquidDesignFieldset($requestedName, $options);
    }
}
