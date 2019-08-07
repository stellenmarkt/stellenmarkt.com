<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Form;

use Zend\Hydrator\HydratorInterface;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class OrganizationsLiquidDesignHydrator implements HydratorInterface
{
    public function extract($object)
    {
        /** @var \Core\Entity\MetaDataProviderInterface $object */
        if ($object->hasMetaData('liquiddesign')) {
            return ['liquidDesign' => $object->getMetaData('liquiddesign')];
        }

        return ['liquidDesign' => '_disabled_'];
    }

    public function hydrate(array $data, $object)
    {
        if (isset($data['liquidDesign'])) {
            $object->setMetaData('liquiddesign', $data['liquidDesign']);
        }

        return $object;
    }
}
