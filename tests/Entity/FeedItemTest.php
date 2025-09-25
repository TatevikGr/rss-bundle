<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\Entity;

use DateTimeImmutable;
use PHPUnit\Framework\TestCase;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;
use TatevikGr\RssBundle\RssFeedBundle\Entity\FeedItem;

class FeedItemTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $feed = new Feed();
        $feed->setUrl('https://example.com/rss');

        $item = new FeedItem();
        $item->setFeed($feed);
        $item->setUid('uid-123');
        $published = new DateTimeImmutable('2020-01-01T00:00:00+00:00');
        $added = new DateTimeImmutable('2020-01-02T00:00:00+00:00');
        $item->setPublished($published);
        $item->setAdded($added);

        $this->assertNull($item->getId());
        $this->assertSame($feed, $item->getFeed());
        $this->assertSame('uid-123', $item->getUid());
        $this->assertSame($published, $item->getPublished());
        $this->assertSame($added, $item->getAdded());
    }
}
