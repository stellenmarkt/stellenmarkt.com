<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class ContentController extends AbstractActionController
{

    public function indexAction()
    {
        $script = $this->params()->fromRoute('script');
        $model = new ViewModel(['script' => $script]);
        $model->setTemplate('gastro24/content/' . $script);

        return $model;
    }
    
}