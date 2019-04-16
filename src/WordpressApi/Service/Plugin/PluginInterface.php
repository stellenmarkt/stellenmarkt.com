<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2017 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\WordpressApi\Service\Plugin;

use Stellenmarkt\WordpressApi\Service\WordpressClientInterface;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
interface PluginInterface
{
    public function setClient(WordpressClientInterface $client);
}