<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\Tests;

use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TatevikGr\RssBundle\RssFeedBundle\RssFeedBundle;

class RssFeedBundleTest extends TestCase
{
    public function testBundleExtendsSymfonyBundle(): void
    {
        $bundle = new RssFeedBundle();
        $this->assertInstanceOf(Bundle::class, $bundle);
        $this->assertSame('RssFeedBundle', $bundle->getName());
    }
}
