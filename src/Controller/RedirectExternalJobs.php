<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */

/** */
namespace Stellenmarkt\Controller;

use Core\Entity\Exception\NotFoundException;
use Stellenmarkt\Session\VisitedJobsContainer;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

/**
 * ${CARET}
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test
 */
class RedirectExternalJobs extends AbstractActionController
{

    /**
     *
     *
     * @var \Stellenmarkt\Validator\IframeEmbeddableUri
     */
    private $validator;

    public function __construct(\Stellenmarkt\Validator\IframeEmbeddableUri $validator)
    {
        $this->validator = $validator;
    }

    public function indexAction()
    {
        /* @var Response $response */
        $response = $this->getResponse();

        try {
            /** @var \Jobs\Entity\Job $job */
            $job = $this->initializeJob()->get($this->params());
        } catch (NotFoundException $e) {
            /** @var Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_404);
            return [
                'message' => 'Kein Job gefunden'
            ];
        }

        $appModel = $this->getEvent()->getViewModel();
        $model = new ViewModel(['job' => $job]);
        $jobTemplate = $job->getOrganization()->hasMetaData('liquiddesign') && $job->getOrganization()->getMetaData('liquiddesign') != '_disabled_'
            ? $job->getOrganization()->getMetaData('liquiddesign')
            : false
        ;

        if (!$job->getLink() || $jobTemplate) {

            $appTemplate = $appModel->getTemplate();

            if ($job->isActive()) {
                $internModel = $this->forward()->dispatch('Jobs/Template', ['internal' => true, 'id' => $job->getId(), 'action' => 'view']);
                $internModel->setTemplate($jobTemplate ?: 'stellenmarkt/jobs/view-intern');
                $model->addChild($internModel, 'internalJob');
            } else {
                $response->setStatusCode(Response::STATUS_CODE_410);
                $model->setVariable('isExpired', true);
            }

            $model->setVariable('isIntern', true);
            // restore application models' template
            $appModel->setTemplate($appTemplate);
        } else {
            $visitedJobsContainer = new VisitedJobsContainer();
            $isVisited            = $this->params()->fromRoute('isPreview') ? false : $visitedJobsContainer->isVisited($job);
            $isEmbeddable         = $this->validator->isValid($job->getLink());

            if (!$isVisited && !$isEmbeddable) {
                $response->getHeaders()->addHeaderLine('Refresh', '4;' . $job->getLink());
                $visitedJobsContainer->add($job);
            }
            $model->setVariables([
                'isVisited' => $isVisited,
                'isEmbeddable' => $isEmbeddable,
            ]);

        }
        $model->setTemplate('stellenmarkt/jobs/view-extern');

        if ($this->params()->fromRoute('isPreview')) {
            $appModel->setVariable('noHeader', true);
            $appModel->setVariable('noFooter', true);
        }

        return $model;
    }
}
