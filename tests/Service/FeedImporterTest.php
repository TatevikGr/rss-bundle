<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\Service;

use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use FeedIo\FeedIo;
use FeedIo\Reader\ReadErrorException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;
use TatevikGr\RssBundle\RssFeedBundle\Repository\FeedItemRepository;
use TatevikGr\RssBundle\RssFeedBundle\Repository\FeedRepository;
use TatevikGr\RssBundle\RssFeedBundle\Service\FeedImporter;

class FeedImporterTest extends TestCase
{
    private EntityManagerInterface&MockObject $em;
    private FeedIo&MockObject $feedIo;
    private FeedRepository&MockObject $feedRepository;
    private FeedItemRepository&MockObject $feedItemRepository;

    protected function setUp(): void
    {
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->feedIo = $this->createMock(FeedIo::class);
        $this->feedRepository = $this->createMock(FeedRepository::class);
        $this->feedItemRepository = $this->createMock(FeedItemRepository::class);
    }

    public function testReturnsZeroWhenFeedNotFound(): void
    {
        $this->feedRepository->method('find')->with(123)->willReturn(null);

        $importer = new FeedImporter($this->em, $this->feedIo, $this->feedRepository, $this->feedItemRepository);
        $this->assertSame(0, $importer->importFeed(123));
    }

    public function testReturnsZeroWhenReadThrows(): void
    {
        $feed = new Feed();
        $feed->setUrl('https://example.com/rss');
        $this->feedRepository->method('find')->with(1)->willReturn($feed);

        $this->feedIo->method('read')->willThrowException(new ReadErrorException('boom'));

        $importer = new FeedImporter($this->em, $this->feedIo, $this->feedRepository, $this->feedItemRepository);
        $this->assertSame(0, $importer->importFeed(1));
    }
}
