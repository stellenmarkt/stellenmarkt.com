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
        $qb = $this->repository->createQueryBuilder();
        $qb->select('organization')->select('dateModified');
        $qb->field('status.name')->equals(\Jobs\Entity\StatusInterface::ACTIVE);
        $qb->hydrate(false);

        $result = $qb->getQuery()->execute()->toArray();

        foreach ($result as $jobId => $data) {
            if (!$this->map->hasTemplate((string) $data['organization'])) {
                continue;
            }

            $link = new RouteLink();
            $link->setName('lang/jobs/view-extern');
            $link->setParams(['id' => $jobId]);
            $link->setLastModified(
                $data['dateModified']['date']
                ->toDateTime()
                ->setTimezone(new \DateTimeZone($data['dateModified']['tz'])
            ));
            $link->setChangeFrequency(Sitemap::MONTHLY);

            $collection->addLink($link);
        }
    }
}
