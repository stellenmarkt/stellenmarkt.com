<?php declare(strict_types=1);
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

namespace Stellenmarkt\Controller;

use Zend\Mvc\Console\Controller\AbstractConsoleController;
use Organizations\Repository\Organization;
use Stellenmarkt\Options\CompanyTemplatesMap;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class ConsoleController extends AbstractConsoleController
{
    private $repository;
    private $map;

    public function __construct(Organization $repository, array $map)
    {
        $this->repository = $repository;
        $this->map = $map;
    }

    public function indexAction()
    {
        foreach ($this->map as $orgId => $templ) {
            /** @var \Organizations\Entity\Organization $org */
            $org = $this->repository->find($orgId);
            $org->setMetaData('liquiddesign', $templ);
            echo sprintf("%s (%s) => %s\n", $org->getOrganizationName()->getName(), $orgId, $templ);
        }
    }
}
