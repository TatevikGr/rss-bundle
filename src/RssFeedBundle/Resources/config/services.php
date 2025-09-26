<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

return static function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('TatevikGr\\RssBundle\\RssFeedBundle\\', __DIR__ . '/../../*')
        ->exclude([
            __DIR__ . '/../../Resources',
        ]);

    // Explicit definitions (if necessary)
};
