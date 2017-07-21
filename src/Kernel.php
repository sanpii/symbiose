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
        return dirname(__DIR__).'/var/cache/'.$this->environment;
    }

    public function getLogDir(): string
    {
        return dirname(__DIR__).'/var/logs';
    }

    public function registerBundles(): iterable
    {
        $contents = require dirname(__DIR__).'/config/bundles.php';
        foreach ($contents as $class => $envs) {
            if (isset($envs['all']) || isset($envs[$this->getEnvironment()])) {
                yield new $class();
            }
        }
    }

    protected function configureContainer(ContainerBuilder $container, LoaderInterface $loader): void
    {
        $confDir = dirname(__DIR__).'/config';
        $loader->import($confDir.'/packages/*.yaml', 'glob');
        if (is_dir($confDir.'/packages/'.$this->getEnvironment())) {
            $loader->import($confDir.'/packages/'.$this->getEnvironment().'/**/*.yaml', 'glob');
        }
        $loader->import($confDir.'/container.yaml', 'glob');
    }

    protected function configureRoutes(RouteCollectionBuilder $routes): void
    {
        $confDir = dirname(__DIR__).'/config';
        if (is_dir($confDir.'/routing/')) {
            $routes->import($confDir.'/routing/*.yaml', '/', 'glob');
        }
        if (is_dir($confDir.'/routing/'.$this->getEnvironment())) {
            $routes->import($confDir.'/routing/'.$this->getEnvironment().'/**/*.yaml', '/', 'glob');
        }
        $routes->import($confDir.'/routing.yaml', '/', 'glob');
    }
}
