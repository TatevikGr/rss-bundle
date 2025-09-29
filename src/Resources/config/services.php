<?php

use FeedIo\FeedIo;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use TatevikGr\RssFeedBundle\Service\FeedIoFactory;

return static function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->set(FeedIo::class)
        ->factory([FeedIoFactory::class, 'create']);

    // Auto-register services from the bundle, excluding non-service directories and the bundle class
    $services->load('TatevikGr\\RssFeedBundle\\', __DIR__ . '/../../')
        ->exclude([
            __DIR__ . '/../../{DependencyInjection,Entity,Resources,Migrations,Tests}',
            __DIR__ . '/../../RssFeedBundle.php',
        ]);

    // Explicit definitions (if necessary)
};
