<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2015 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Gastro24\Form;

use Core\Form\SummaryForm;

/**
 * ${CARET}
 * 
 * @author Carsten Bleek <bleek@cross-solution.de>
 */
class ClassificationForm extends SummaryForm
{
    protected $displayMode = self::DISPLAY_SUMMARY;

    protected $baseFieldset = 'Gastro24/ClassificationFieldset';
    
}