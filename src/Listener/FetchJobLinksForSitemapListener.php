<?php
/**
 * Stellenmarkt
 *
 * @filesource
 * @copyright 2019 CROSS Solution <https://www.cross-solution.de>
 * @license MIT
 */

declare(strict_types=1);

namespace Stellenmarkt\Listener;

use Sitemap\Event\GenerateSitemapEvent;
use Sitemap\Entity\RouteLink;
use samdark\sitemap\Sitemap;
use Stellenmarkt\Options\CompanyTemplatesMap;
use Sitemap\Entity\UrlLink;

/**
 * TODO: description
 *
 * @author Mathias Gelhausen <gelhausen@cross-solution.de>
 * TODO: write tests
 */
class FetchJobLinksForSitemapListener
{
    /** @var \Jobs\Repository\Job */
    private $repository;
    private $map;

    public function __construct(\Jobs\Repository\Job $repository, CompanyTemplatesMap $map)
    {
        $this->repository = $repository;
        $this->map = $map;
    }

    public function __invoke(GenerateSitemapEvent $event)
    {
        $collection = $event->getLinkCollection();
        $jobs = $this->repository->findBy(['status.name' => \Jobs\Entity\StatusInterface::ACTIVE]);

        /** @var \Jobs\Entity\Job $job */
        foreach ($jobs as $job) {
            if (!$this->map->hasTemplate($job->getOrganization())) {
                continue;
            }

            $link = new RouteLink();
            $link->setName('lang/jobs/view-extern');
            $link->setParams(['id' => $job->getId()]);
            $link->setLastModified($job->getDateModified());
            $link->setChangeFrequency(Sitemap::MONTHLY);

            $collection->addLink($link);
        }
    }
}
