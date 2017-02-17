<?php

namespace AppBundle\DependencyInjection;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * Loads the config for the app.
 * @author lange <lange@bestit-online.de>
 * @package AppBundle
 * @subpackage DependencyInjection
 * @version $id$
 */
class AppExtension extends Extension
{
    /**
     * Loads the bundle config.
     * @param array $configs
     * @param ContainerBuilder $container
     * @return void
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yml');

        $config = $this->processConfiguration(new Configuration(), $configs);

        $container->setAlias(
            'app.export.filesystem',
            $config['filesystem']
        );

        $container->setParameter(
            'app.commercetools.client.id',
            (string) @ $config['commercetools_client']['id']
        );

        $container->setParameter(
            'app.commercetools.client.secret',
            (string) @ $config['commercetools_client']['secret']
        );

        $container->setParameter(
            'app.commercetools.client.project',
            (string) @ $config['commercetools_client']['project']
        );

        $container->setParameter(
            'app.commercetools.client.scope',
            (string) @ $config['commercetools_client']['scope']
        );

        $container->setParameter(
            'app.orders.with_pagination',
            (bool) ( $config['orders']['with_pagination'] ?? true )
        );

        $container->setParameter('app.orders.default_where', $config['orders']['default_where'] ?? []);
        $container->setParameter('app.orders.file_template', $config['orders']['file_template']);
        $container->setParameter('app.orders.name_scheme', $config['orders']['name_scheme']);
    }
}
