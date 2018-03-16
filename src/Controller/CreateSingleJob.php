<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\Session\Container;
use Zend\View\Helper\ServerUrl;
use Zend\View\Model\JsonModel;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class CreateSingleJob extends AbstractActionController
{
    /**
     *
     * @var \Gastro24\Form\CreateSingleJobForm
     */
    private $form;

    public function __construct($form)
    {
        $this->form = $form;
    }

    public function indexAction()
    {
        /* @var \Zend\Http\PhpEnvironment\Request $request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            return $this->process();
        }

        if ('complete' == $request->getQuery('do')) {
            return $this->complete();
        }

        return [
            'form' => $this->form,
        ];
    }

    private function process()
    {
        $data = array_merge_recursive($this->getRequest()->getPost()->toArray(), $this->getRequest()->getFiles()->toArray());
        $this->form->setData($data);

        if (!$this->form->isValid()) {
            return [
                'valid' => false,
                'form' => $this->form,
            ];
        }

        $values = $this->form->getData();

        if ('pdf' == $values['mode']) {
            $serverUrl = new ServerUrl();
            $basePath  = $this->getRequest()->getBasePath();

            $values['uri'] = $serverUrl('/' . $basePath . str_replace('public/', '', $values['pdf']['tmp_name']));
        }

        $session = new Container('Gastro24_SingleJobData');
        $session->data = serialize($data);
        $session->values = serialize($values);

        $values['isProcessed'] = true;

        return $values;
    }

    private function complete()
    {
        $session = new Container('Gastro24_SingleJobData');
        $values  = $session->values;

        if (!$values) {
            return $this->redirect()->toRoute('lang/jobs/single');
        }

        $plugin = $this->plugin(Plugin\CreateSingleJob::class);

        try {
            $plugin(unserialize($values));
        } catch (\Exception $e) {
            return [
                'isError' => true,
            ];
        }

        $session->getManager()->getStorage()->clear('Gastro24_SingleJobData');

        return [ 'isSuccess' => true ];

    }

}
