<?php

declare(strict_types=1);

namespace TatevikGr\RssFeedBundle;

use Symfony\Component\DependencyInjection\Extension\ExtensionInterface;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use TatevikGr\RssFeedBundle\DependencyInjection\RssFeedExtension;

class RssFeedBundle extends Bundle
{
    public function getContainerExtension(): ?ExtensionInterface
    {
        return new RssFeedExtension();
    }
}
