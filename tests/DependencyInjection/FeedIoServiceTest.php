<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TatevikGr\RssBundle\RssFeedBundle\DependencyInjection\RssFeedExtension;
use FeedIo\FeedIo;

class FeedIoServiceTest extends TestCase
{
    public function testFeedIoServiceIsRegistered(): void
    {
        $container = new ContainerBuilder();
        $extension = new RssFeedExtension();

        $extension->load([], $container);

        $this->assertTrue(
            $container->has(FeedIo::class),
            'Expected FeedIo service to be registered in the container.'
        );
    }
}
