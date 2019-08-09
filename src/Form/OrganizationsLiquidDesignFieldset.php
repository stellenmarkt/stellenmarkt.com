<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Form;

use Zend\Form\Fieldset;
use Core\Form\Element\Select;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class OrganizationsLiquidDesignFieldset extends Fieldset
{
    private $selectValues = [];

    public function setOptions($options)
    {
        parent::setOptions($options);

        if (isset($options['selectValues'])) {
            $this->selectValues = $options['selectValues'];
        }
    }

    /**
     * Gets the Hydrator
     *
     * @return \Zend\Hydrator\HydratorInterface
     */
    public function getHydrator()
    {
        if (!$this->hydrator) {
            $this->setHydrator(new OrganizationsLiquidDesignHydrator());
        }

        return $this->hydrator;
    }

    public function init()
    {
        $this->setName('liquid-design');
        $this->add([
            'type' => Select::class,
            'name' => 'liquidDesign',
            'value' => '_disabled_',
            'options' => [
                'label' => /*@translate*/ 'Liquid-Design-Anzeigen',
                'value_options' => $this->selectValues
            ],
        ]);
    }

    /**
     * a required method to overwrite the generic method to make the binding of the entity work
     * @param object $object
     * @return bool
     */
    public function allowObjectBinding($object)
    {
        return $object instanceof Organization;
    }
}
