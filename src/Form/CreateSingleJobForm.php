<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Form;

use Core\Form\Form;
use Core\Form\ViewPartialProviderInterface;
use Core\Form\ViewPartialProviderTrait;
use Jobs\Entity\Location;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJobForm extends Form implements InputFilterProviderInterface, ViewPartialProviderInterface
{
    private $defaultPartial = 'stellenmarkt/form/create-single-job';

    use ViewPartialProviderTrait;

    public function init()
    {
        $this->setIsDescriptionsEnabled(true);
        $this->setDescription('Hier können Sie ein Einzelinserat aufgeben');
        $this->setAttribute('id', 'createsinglejob');

        $this->add([
            'type' => 'Text',
            'name' => 'title',
            'options' => [
                'description' => 'Geben Sie den Job-Titel ein.',
                'label' => 'Titel',
            ],
            'attributes' => [
                'require' => true,
                'id' => 'csj-title'
            ]
        ]);

        $this->add([
            'type' => 'LocationSelect',
            'name' => 'locations',
            'options' => [
                'description' => 'Geben Sie den Beschäftigungsort an.<br><br>Ihre Vorschläge werden auto-vervollständigt.',
                'label' => 'Ort',
                'location_entity' => Location::class
            ],
            'attributes' => [
                'required' => true,
                'multiple' => true,
                'data-placeholder' => '',
                'id' => 'csj-locations',
            ],
        ]);

        $this->add([
            'name' => 'details',
            'type' => JobDetails::class,
            'options' => [
                'label' => 'Details',
            ],
        ]);


        $this->add([
            'type' => 'Orders/InvoiceAddressFieldset',
            'options' => [
                'label' => 'Rechnungsanschrift',
            ],
        ]);

        $this->add([
            'type' => 'DefaultButtonsFieldset',
            'name' => 'buttons',
            'options' => [
                'save_label' => 'Weiter zur Vorschau',
            ]
        ]);

    }

    public function getInputFilterSpecification()
    {
        $spec = [

                'title' => [
                    'require' => true,
                ],

                'locations' => [
                    'require' => true,
                ],

        ];



        return $spec;
    }

    public function isValid()
    {
        return \Zend\Form\Form::isValid(); // TODO: Change the autogenerated stub
    }


}
