<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\Entity;

use PHPUnit\Framework\TestCase;
use TatevikGr\RssBundle\RssFeedBundle\Entity\Feed;
use TatevikGr\RssBundle\RssFeedBundle\Entity\FeedItem;
use TatevikGr\RssBundle\RssFeedBundle\Entity\ItemData;

class ItemDataTest extends TestCase
{
    public function testGettersAndSetters(): void
    {
        $feed = new Feed();
        $item = new FeedItem();
        $item->setFeed($feed);
        $item->setUid('uid-xyz');
        $itemData = new ItemData();
        $itemData->setItem($item);
        $itemData->setProperty('title');
        $itemData->setValue('Hello World');

        $this->assertSame($item, $itemData->getItem());
        $this->assertSame('title', $itemData->getProperty());
        $this->assertSame('Hello World', $itemData->getValue());

        $itemData->setValue(null);
        $this->assertNull($itemData->getValue());
    }
}
