<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests\DependencyInjection;

use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use TatevikGr\RssBundle\RssFeedBundle\Command\RssDispatchCommand;
use TatevikGr\RssBundle\RssFeedBundle\DependencyInjection\RssFeedExtension;

class RssFeedExtensionTest extends TestCase
{
    public function testLoadRegistersServicesSuccessfully(): void
    {
        $container = new ContainerBuilder();
        $extension = new RssFeedExtension();

        $extension->load([], $container);

        $this->assertTrue($container->has(RssDispatchCommand::class), 'Expected RssDispatchCommand service to be registered.');
    }
}
