<?php

use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\Reference;

return static function (ContainerConfigurator $config): void {
    $services = $config->services();

    $services->defaults()
        ->autowire()
        ->autoconfigure();

    $services->load('RssFeed\\Bundle\\', __DIR__ . '/../../*')
        ->exclude([
            __DIR__ . '/../../Resources',
        ]);

    // Explicit definitions (if necessary)
};
