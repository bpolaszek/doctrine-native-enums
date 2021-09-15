<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Type;

/**
 * @internal
 */
enum BackedEnumType
{
    case STRING;
    case INT;

    public function cast(mixed $value): int|string
    {
        return match ($this) {
            self::STRING => (string) $value,
            self::INT => (int) $value,
        };
    }
}
