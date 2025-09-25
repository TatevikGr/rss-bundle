<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TatevikGr\RssBundle\RssFeedBundle\Entity\ItemData;
use TatevikGr\RssBundle\RssFeedBundle\Entity\FeedItem;

/**
 * @extends ServiceEntityRepository<ItemData>
 */
class ItemDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ItemData::class);
    }

    /**
     * @return array<string, string|null>
     */
    public function findPropertyMapForItem(FeedItem $item): array
    {
        $rows = $this->createQueryBuilder('d')
            ->select('d.property, d.value')
            ->andWhere('d.item = :item')
            ->setParameter('item', $item)
            ->getQuery()
            ->getArrayResult();

        $map = [];
        foreach ($rows as $row) {
            $map[$row['property']] = $row['value'];
        }
        return $map;
    }
}
