<?php
/**
 * YAWIK
 *
 * @filesource
 * @license MIT
 * @copyright  2013 - 2018 Cross Solution <http://cross-solution.de>
 */
  
/** */
namespace Stellenmarkt\ContactForm\Form;

use Core\Factory\Form\AbstractCustomizableFieldsetFactory;
use Stellenmarkt\ContactForm\Options\ContactFormFormOptions;

/**
 * Factory for \Stellenmarkt\ContactForm\Form\ContactForm
 * 
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * @todo write test  
 */
class ContactFormFactory extends AbstractCustomizableFieldsetFactory
{
    const CLASS_NAME = ContactForm::class;
    const OPTIONS_NAME = ContactFormFormOptions::class;

}
