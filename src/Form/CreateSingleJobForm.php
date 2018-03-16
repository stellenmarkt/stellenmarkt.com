<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Form;

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
    private $defaultPartial = 'gastro24/form/create-single-job';

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
            'type' => 'radio',
            'name' => 'mode',
            'options' => [
                'value_options' => [
                    'uri' => 'uri',
                    'pdf' => 'pdf',
                ],
            ],
            'attributes' => [
                'value' => 'uri',
            ],
        ]);

        $this->add([
            'type' => 'Text',
            'name' => 'uri',
            'options' => [
                'description' => 'Geben Sie die Online-Addresse des Inserats an.',
                'label' => 'Online-Inserat',
                'rowClass' => 'csj-uri-wrapper'
            ],
            'attributes' => [
                'placeholder' => 'http://',
                'id' => 'csj-uri',
            ]
        ]);

        $this->add([
            'type' => 'File',
            'name' => 'pdf',
            'options' => [
                'description' => 'Selektieren Sie hier das PDF-Dokument Ihres Jobs.',
                'label' => 'PDF-Datei',
                'rowClass' => 'csj-pdf-wrapper',
            ],
            'attributes' => [
                'id' => 'csj-pdf',
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
                'save_label' => 'Inserat anlegen',
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

        if (isset($this->data['mode'])) {
            if ('pdf' == $this->data['mode']) {
                $spec['pdf'] = [
                    'require' => true,
                    'validators' => [
                        [
                            'name' => 'FileMimeType',
                            'options' => [
                                'mimeType' => 'application/pdf',
                                'disableMagicFile' => true,
                                'magicFile' => false,
                            ]
                        ],
                        [
                            'name' => 'FileExtension',
                            'options' => [
                                'extension' => 'pdf',
                            ],
                        ],
                    ],
                    'filters' => [
                        [
                            'name' => 'FileRenameUpload',
                            'options' => [
                                'target' =>  'public/static/jobs/job.pdf',
                                'randomize' => true,
                            ],
                        ],
                    ],
                ];
            } else {
                $spec['uri'] = [ 'require' => true ];
            }
        }

        return $spec;
    }

    public function isValid()
    {
        return \Zend\Form\Form::isValid(); // TODO: Change the autogenerated stub
    }


}
