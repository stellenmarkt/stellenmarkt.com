<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\Entity\Hydrator;

use Core\Entity\Hydrator\EntityHydrator;
use Jobs\Entity\JobSnapshot;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobHydrator extends EntityHydrator
{
    public function extract($object)
    {
        $data = parent::extract($object);

        $template = $object->getAttachedEntity('stellenmarkt-template');

        if ($template) {
            $data['stellenmarkt-template'] = $template;
        }

        return $data;
    }

    public function hydrate(array $data, $object)
    {
        if (isset($data['stellenmarkt-template'])) {
            /* @var \Stellenmarkt\Entity\Template $template */
            $template = $data['stellenmarkt-template'];
            $logo = $template->getLogo();
            $image = $template->getImage();

            $newTemplate = $object->getAttachedEntity('stellenmarkt-template');
            if (!$newTemplate) {
                $newTemplate = new \Stellenmarkt\Entity\Template();
                $object->addAttachedEntity($template, 'stellenmarkt-template');
            }

            $logo  && $newTemplate->setLogo($logo);
            $image && $newTemplate->setImage($image);

            unset($data['stellenmarkt-template']);
        }

        return parent::hydrate($data, $object);
    }


}
