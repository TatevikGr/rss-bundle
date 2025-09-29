<?php

declare(strict_types=1);

namespace TatevikGr\RssBundle\RssFeedBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;

class RssFeedExtension extends Extension implements PrependExtensionInterface
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.php');
    }

    public function prepend(ContainerBuilder $container): void
    {
        $container->prependExtensionConfig('doctrine_migrations', [
            'migrations_paths' => [
                'TatevikGr\\RssBundle\\RssFeedBundle\\Migrations' =>
                    \dirname(__DIR__).'/Migrations',
            ],
        ]);

        $container->prependExtensionConfig('doctrine', [
            'orm' => [
                'mappings' => [
                    'TatevikGrRssBundle' => [
                        'is_bundle' => false,
                        'type' => 'attribute',
                        'dir' => __DIR__.'/../Entity',
                        'prefix' => 'TatevikGr\\RssBundle\\RssFeedBundle\\Entity',
                        'alias' => 'RssBundle',
                    ],
                ],
            ],
        ]);
    }
}
