<?php
declare(strict_types = 1);

namespace App;

use \Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use \Symfony\Component\Config\Loader\LoaderInterface;
use \Symfony\Component\DependencyInjection\ContainerBuilder;
use \Symfony\Component\HttpKernel\Kernel as HttpKernel;
use \Symfony\Component\Routing\RouteCollectionBuilder;

final class Kernel extends HttpKernel
{
    use MicroKernelTrait;

    public function getCacheDir(): string
    {
        return dirname(__DIR__) . '/var/cache/' . $this->getEnvironment();
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__) . '/var/logs';
    }

    public function registerBundles(): iterable
    {
        $contents = require dirname(__DIR__) . '/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->getEnvironment()])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = dirname(__DIR__) . '/config';
        $loader->load($confDir . '/packages/*.yaml', 'glob');
        if (is_dir($confDir . '/packages/' . $this->getEnvironment())) {
            $loader->load($confDir . '/packages/' . $this->getEnvironment() . '/**/*.yaml', 'glob');
        }
        $loader->load($confDir . '/services.yaml', 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = dirname(__DIR__) . '/config';
        if (is_dir($confDir . '/routes/')) {
            $routes->import($confDir . '/routes/*.yaml', '/', 'glob');
        }
        if (is_dir($confDir . '/routes/' . $this->getEnvironment())) {
            $routes->import($confDir . '/routes/' . $this->getEnvironment() . '/**/*.yaml', '/', 'glob');
        }
        $routes->import($confDir . '/routes.yaml', '/', 'glob');
    }
}
