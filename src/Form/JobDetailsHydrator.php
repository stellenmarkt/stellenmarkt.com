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

use Zend\Hydrator\HydratorInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobDetailsHydrator implements HydratorInterface
{
    public function extract($object)
    {
        $link = $object->getLink();
        if ('.pdf' == substr($link, -4)) {
            $mode = 'pdf';
        } else if ($link) {
            $mode = 'uri';
        } else {
            $mode = 'html';
        }

        return [
            'mode' => $mode,
            'uri' => $mode == 'uri' ? $link : '',
            'pdf' => $mode == 'pdf' ? $link : '',
            //'description' => $object->getTemplateValues()->getDescription(),
            'position' => $object->getTemplateValues()->get('position'),
        ];
    }

    public function hydrate(array $data, $object)
    {
        if ('html' == $data['mode']) {
            $link = $object->getLink();
            if ('.pdf' == substr($link, -4)) {
                @unlink('public/' . $link);
            }
            $object->setLink('');
            $object->getTemplateValues()->setDescription($data['description']);
            $object->getTemplateValues()->position = $data['position'];
        } else {
            $object->setLink('uri' == $data['mode'] ? $data['uri'] : (isset($_POST['pdf_uri']) ? $_POST['pdf_uri'] : $data['pdf']));
        }

        return $object;
    }

}
