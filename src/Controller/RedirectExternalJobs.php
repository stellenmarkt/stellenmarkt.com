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

use Core\Entity\Exception\NotFoundException;
use Gastro24\Session\VisitedJobsContainer;
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
     * @var \Gastro24\Validator\IframeEmbeddableUri
     */
    private $validator;

    public function __construct(\Gastro24\Validator\IframeEmbeddableUri $validator)
    {
        $this->validator = $validator;
    }

    public function indexAction()
    {
        /* @var Response $response */
        $response = $this->getResponse();

        try {
            /* @var \Jobs\Entity\JobInterface $job */
            $job = $this->initializeJob()->get($this->params());
        } catch (NotFoundException $e) {
            /* @var Response $response */
            $response = $this->getResponse();
            $response->setStatusCode(Response::STATUS_CODE_404);
            return [
                'message' => 'Kein Job gefunden'
            ];
        }

        $visitedJobsContainer = new VisitedJobsContainer();
        $isVisited            = $visitedJobsContainer->isVisited($job);
        $isEmbeddable         = $this->validator->isValid($job->getLink());

        if (!$isVisited && !$isEmbeddable) {
            $response->getHeaders()->addHeaderLine('Refresh', '4;' . $job->getLink());
            $visitedJobsContainer->add($job);
        }

        $model = new ViewModel([
            'job' => $job,
            'isVisited' => $isVisited,
            'isEmbeddable' => $isEmbeddable,
        ]);
        $model->setTemplate('gastro24/jobs/view-extern');

        return $model;
    }
}
