<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Form;

use Zend\ServiceManager\Factory\DelegatorFactoryInterface;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class OrganizationsFormDelegatorFactory implements DelegatorFactoryInterface
{
    public function __invoke(
        \Interop\Container\ContainerInterface $container,
        $name,
        callable $callback,
        ?array $options = null
    ) {
        $form = $callback();

        $form->setForm('liquiddesign', [
            'type' => OrganizationsLiquidDesignForm::class,
            'property' => true,
            'options' => [
                'enable_descriptions' => true,
                'description' => 'Als LiquidDesign-Anzeige behandeln? Template auswählen.',
            ],
            'priority' => -2,
        ]);

        return $form;
    }
}
