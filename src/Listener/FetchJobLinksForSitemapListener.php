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

    public function __construct(\Jobs\Repository\Job $repository)
    {
        $this->repository = $repository;
    }

    public function __invoke(GenerateSitemapEvent $event)
    {
        $collection = $event->getLinkCollection();
        $qb = $this->repository->createQueryBuilder();
        $qb->select('organization')->select('dateModified');
        $qb->field('status.name')->equals(\Jobs\Entity\StatusInterface::ACTIVE);
        $qb->readOnly()->hydrate(true);

        $result = $qb->getQuery()->execute()->toArray();

        foreach ($result as $jobId => $job) {
            $org = $job->getOrganization();
            if (!$org->hasMetaData('liquiddesign') || $org->getMetaData('liquiddesign') == '_disabled_') {
                continue;
            }

            $link = new RouteLink();
            $link->setName('lang/jobs/view-extern');
            $link->setParams(['id' => $job->getId()]);
            $link->setLastModified(
                $job->getDateModified()
            );
            $link->setChangeFrequency(Sitemap::DAILY);
            $link->setPriority(0.6);

            $collection->addLink($link);
        }
    }
}
