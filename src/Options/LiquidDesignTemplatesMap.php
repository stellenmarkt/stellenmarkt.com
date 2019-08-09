<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Options;

use Zend\Stdlib\AbstractOptions;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class LiquidDesignTemplatesMap extends AbstractOptions
{
    /**
     * @var string[]
     */
    private $map = [];

    public function setMap(array $map): void
    {
        $this->map = $map;
    }

    public function getMap(): array
    {
        return $this->map;
    }

    public function getMapAsSelectValues(): array
    {
        return ['_disabled_' => 'deaktiviert'] + array_flip($this->getMap());
    }
}
