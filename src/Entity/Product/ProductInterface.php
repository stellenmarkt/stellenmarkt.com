<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Entity\Product;

/**
 * ${CARET}
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test 
 */
interface ProductInterface 
{
    /**
     *
     *
     * @return \DateTime
     */
    public function getStartDate();

    /**
     *
     *
     * @return \DateTime
     */
    public function getEndDate();

    /**
     *
     *
     * @return int
     */
    public function getJobCount();

    /**
     *
     *
     * @return bool
     */
    public function hasAvailableJobAmount();

    /**
     *
     *
     * @return void
     */
    public function decreaseJobCount();

    /**
     *
     *
     * @return void
     */
    public function increaseJobCount();
}
