<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Bundle;

use BenTools\Doctrine\NativeEnums\Type\NativeEnum;
use Doctrine\DBAL\Types\Type;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @codeCoverageIgnore
 */
final class DoctrineNativeEnumsBundle extends Bundle
{
    public function boot(): void
    {
        $config = $this->container->getParameter('doctrine_native_enums.enum_types') ?? [];
        foreach ($config as $enumClass => $enumType) {
            $enumType ??= $enumClass;
            if (!Type::hasType($enumType)) {
                NativeEnum::registerEnumType($enumType, $enumClass);
            }
        }
    }
}
