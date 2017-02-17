<?php

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Routing\RouteCollectionBuilder;

/**
 * The App kernel.
 * @author Bjoern Lange <lange@bestit-online.de>
 * @category app
 * @version $id
 */
class AppKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * Configs the container.
     * @param ContainerBuilder $c
     * @param LoaderInterface $loader
     * @return void
     */
    protected function configureContainer(ContainerBuilder $c, LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/config/config_' . $this->getEnvironment() . '.yml');
    }

    /**
     * Registers no routes.
     * @param RouteCollectionBuilder $routes
     * @return void
     */
    protected function configureRoutes(RouteCollectionBuilder $routes)
    {
        // Do nothing.
    }

    /**
     * Returns the cache dir.
     * @return string
     */
    public function getCacheDir()
    {
        return __DIR__ . '/../var/cache/' . $this->getEnvironment();
    }

    /**
     * Returns the log dir.
     * @return string
     */
    public function getLogDir()
    {
        return __DIR__ . '/../var/logs';
    }

    /**
     * Registers the bundle.
     * @return Bundle[]
     */
    public function registerBundles()
    {
        return [
            new \Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new \Symfony\Bundle\MonologBundle\MonologBundle(),
            new \Symfony\Bundle\TwigBundle\TwigBundle(),
            new \Oneup\FlysystemBundle\OneupFlysystemBundle(),
            new \BestIt\CtOrderExportBundle\BestItCtOrderExportBundle()
        ];
    }
}
