<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Exception\InvalidArgumentException;
use TatevikGr\RssBundle\RssFeedBundle\DependencyInjection\RssFeedExtension;

class RssFeedExtensionTest extends TestCase
{
    public function testLoadThrowsDueToMisconfiguredServicesNamespace(): void
    {
        $container = new ContainerBuilder();
        $extension = new RssFeedExtension();

        $this->expectException(InvalidArgumentException::class);
        $extension->load([], $container);
    }
}
