<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\Service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;
use TatevikGr\RssBundle\RssFeedBundle\Entity\FeedItem;
use TatevikGr\RssBundle\RssFeedBundle\Entity\ItemData;
use Tatevikgr\RssBundle\RssFeedBundle\Repository\FeedItemRepository;
use Tatevikgr\RssBundle\RssFeedBundle\Repository\FeedRepository;

class FeedImporter
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FeedIo $feedIo,
        private readonly FeedRepository $feedRepository,
        private readonly FeedItemRepository $feedItemRepository,
    ) {}

    public function importFeed(int $feedId): int
    {
        $feed = $this->feedRepository->find($feedId);
        if (!$feed) {
            return 0;
        }

        try {
            $result = $this->feedIo->read($feed->getUrl());
        } catch (ReadErrorException $e) {
            // Swallow read errors but do not crash the worker
            return 0;
        }

        $imported = 0;
        $now = new DateTimeImmutable();

        foreach ($result->getFeed() as $item) {
            $uid = (string)($item->getPublicId() ?? $item->getLink() ?? $item->getTitle() ?? spl_object_hash($item));

            // Check for existing item for this feed by uid
            if ($this->feedItemRepository->existsByFeedAndUid($feed, $uid)) {
                continue;
            }

            $fi = new FeedItem();
            $fi->setFeed($feed);
            $fi->setUid($uid);
            $fi->setPublished($item->getLastModified() ?? $item->getMedias()->getLastModified() ?? $now);
            $fi->setAdded($now);
            $this->em->persist($fi);
            $this->em->flush(); // need id for item_data FK

            $this->persistItemData($fi, 'title', (string) $item->getTitle());
            $this->persistItemData($fi, 'content', (string) ($item->getContent() ?? $item->getDescription() ?? ''));
            $this->persistItemData($fi, 'url', (string) $item->getLink());

            $imported++;
        }

        $this->em->flush();
        return $imported;
    }

    private function persistItemData(FeedItem $item, string $property, ?string $value): void
    {
        $id = new ItemData();
        $id->setItem($item);
        $id->setProperty($property);
        $id->setValue($value);
        $this->em->persist($id);
    }
}
