<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\View\Helper;

use Jobs\Entity\Job;
use Jobs\View\Helper\JobUrl;
use Zend\View\Helper\AbstractHelper;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobUrlDelegator extends AbstractHelper
{

    private $jobUrlHelper;

    public function __construct(JobUrl $jobUrlHelper)
    {
        $this->jobUrlHelper = $jobUrlHelper;
    }

    public function __invoke(Job $jobEntity, $options = [], $urlParams = [])
    {
        if (!isset($urlParams['id'])) {
            $urlParams['id'] = $jobEntity->getId();
        }

        $link = $this->jobUrlHelper->__invoke($jobEntity, $options, $urlParams);

        if (false !== strpos($link, '.html')) {
            if (isset($options['linkOnly']) && $options['linkOnly']) {
                $link = preg_replace('\?.*$~', '', $link);
            } else {
                $link = preg_replace('~\?[^"\']*~', '', $link);
            }
        }

        return $link;
    }


    /* Proxy methods */

    public function setUrlHelper($helper)
    {
        return $this->jobUrlHelper->setUrlHelper($helper);
    }

    public function setParamsHelper($helper)
    {
        return $this->jobUrlHelper->setParamsHelper($helper);
    }

    public function setServerUrlHelper($helper)
    {
        return $this->jobUrlHelper->setServerUrlHelper($helper);
    }

    public function setOptions($options)
    {
        return $this->jobUrlHelper->setOptions($options);
    }
}
