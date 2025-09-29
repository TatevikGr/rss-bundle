<?php

declare(strict_types=1);

namespace TatevikGr\RssFeedBundle\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use TatevikGr\RssFeedBundle\Entity\FeedItem;
use TatevikGr\RssFeedBundle\Entity\ItemData;

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
