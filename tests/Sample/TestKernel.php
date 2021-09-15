<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Tests\Sample;

use BenTools\Doctrine\NativeEnums\Bundle\DoctrineNativeEnumsBundle;
use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Bundle\FrameworkBundle\Test\TestContainer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\DependencyInjection\ServiceLocator;
use Symfony\Component\HttpKernel\Kernel;

use function Symfony\Component\DependencyInjection\Loader\Configurator\abstract_arg;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;
use function sys_get_temp_dir;

final class TestKernel extends Kernel
{
    use MicroKernelTrait;

    public function registerBundles(): iterable
    {
        return [
            new FrameworkBundle(),
            new DoctrineBundle(),
            new DoctrineNativeEnumsBundle(),
        ];
    }

    public function configureContainer(ContainerConfigurator $container): void
    {
        $container->services()

            ->set('test.service_container', TestContainer::class)
            ->args([
                service('kernel'),
                'test.private_services_locator',
            ])
            ->public()

            ->set('test.private_services_locator', ServiceLocator::class)
            ->args([abstract_arg('callable collection')])
            ->public()
        ;

        $container->import(__DIR__ . '/config/{packages}/*.yaml');
    }

    public function getProjectDir(): string
    {
        return __DIR__;
    }

    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/sf-cache';
    }

    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/sf-log';
    }
}
