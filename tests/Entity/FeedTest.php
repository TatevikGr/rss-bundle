<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;

class FeedTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $feed = new Feed();

        // defaults
        $this->assertSame('', $feed->getUrl());
        $this->assertSame('', $feed->getEtag());
        $this->assertSame('', $feed->getLastModified());
        $this->assertNull($feed->getId());

        // set values
        $feed->setUrl('https://example.com/feed.xml');
        $feed->setEtag('W/"123"');
        $feed->setLastModified('Thu, 01 Jan 1970 00:00:00 GMT');

        $this->assertSame('https://example.com/feed.xml', $feed->getUrl());
        $this->assertSame('W/"123"', $feed->getEtag());
        $this->assertSame('Thu, 01 Jan 1970 00:00:00 GMT', $feed->getLastModified());
    }
}
