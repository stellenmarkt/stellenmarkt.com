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

use Jobs\Entity\JobInterface;
use Zend\View\Helper\AbstractHelper;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
class JobboardApplyUrl extends AbstractHelper
{
    private $urlHelper;

    public function __construct($urlHelper)
    {
        $this->urlHelper = $urlHelper;
    }

    public function __invoke(JobInterface $job)
    {
        $ats = $job->getAtsMode();

        if ($ats->isDisabled()) {
            $url = $job->getLink();
            $class = "no-apply-link";
        } else if ($ats->isIntern() || $ats->isEmail()) {

            $route = 'lang/apply';
            $params = [
                'applyId' => $job->getApplyId(),
                'lang' => 'de',
            ];

            $url  = $this->urlHelper->__invoke($route, $params);
            $class = 'internal-apply-link';
        } else {
            $url = $ats->getUri();
            $class = 'external-apply-link';
        }

        return sprintf('<a href="%s" class="%s">%s</a>', $url, $class, 'Bewerben');
    }
}
