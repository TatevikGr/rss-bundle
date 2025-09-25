<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Repository;

use Doctrine\Persistence\ManagerRegistry;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;
use TatevikGr\RssBundle\RssFeedBundle\Entity\FeedItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * @extends ServiceEntityRepository<FeedItem>
 */
class FeedItemRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FeedItem::class);
    }

    public function existsByFeedAndUid(Feed $feed, string $uid): bool
    {
        $qb = $this->createQueryBuilder('i')
            ->select('1')
            ->andWhere('i.feed = :feed')
            ->andWhere('i.uid = :uid')
            ->setMaxResults(1)
            ->setParameters([
                'feed' => $feed,
                'uid' => $uid,
            ]);

        return (bool) $qb->getQuery()->getOneOrNullResult();
    }
}
