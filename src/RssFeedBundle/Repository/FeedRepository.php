<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;

/**
 * @extends ServiceEntityRepository<Feed>
 */
class FeedRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Feed::class);
    }

    /**
     * @return int[]
     */
    public function findAllIds(): array
    {
        $rows = $this->createQueryBuilder('f')
            ->select('f.id')
            ->getQuery()
            ->getScalarResult();

        return array_map(static fn(array $r) => (int) $r['id'], $rows);
    }
}
