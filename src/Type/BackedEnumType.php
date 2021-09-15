<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Type;

/**
 * @internal
 */
enum BackedEnumType: string
{
    case STRING = 'string';
    case INT = 'int';

    public function cast(mixed $value): int|string
    {
        return match ($this) {
            self::STRING => (string) $value,
            self::INT => (int) $value,
        };
    }
}
