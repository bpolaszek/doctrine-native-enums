<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Bundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;

/**
 * @codeCoverageIgnore
 */
final class DoctrineNativeEnumsExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);
        $container->setParameter('doctrine_native_enums.enum_types', $config['enum_types']);
    }
}
