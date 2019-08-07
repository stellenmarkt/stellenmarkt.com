<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Form;

use Core\Form\SummaryForm;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class OrganizationsLiquidDesignForm extends SummaryForm
{
    protected $baseFieldset = OrganizationsLiquidDesignFieldset::class;

    protected $displayMode = self::DISPLAY_SUMMARY;
}
