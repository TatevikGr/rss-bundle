<?php

use FeedIo\FeedIo;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TatevikGr\RssBundle\RssFeedBundle\Service\FeedIoFactory;

return static function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FeedIo::class)
        ->factory([FeedIoFactory::class, 'create']);

    $services->load('TatevikGr\\RssBundle\\RssFeedBundle\\', __DIR__ . '/../../*')
        ->exclude([
            __DIR__ . '/../../Resources',
        ]);

    // Explicit definitions (if necessary)
};
