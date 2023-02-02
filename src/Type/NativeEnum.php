<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Type;

use BackedEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use ReflectionEnum;

use function is_a;
use function sprintf;

class NativeEnum extends Type
{
    protected string $name;
    protected string $class;
    protected BackedEnumType $type;

    public static function registerEnumType(string $enumType, ?string $enumClass = null): void
    {
        $enumClass ??= $enumType;
        if (!is_a($enumClass, BackedEnum::class, true)) {
            throw new InvalidArgumentException(sprintf('Class `%s` is not a valid enum.', $enumClass));
        }

        self::addType($enumType, self::class);
        $type = self::getType($enumType);
        $type->name = $enumType;
        $type->class = $enumClass;
        $type->type = self::detectEnumType($enumClass);
    }

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        return $this->type === BackedEnumType::INT
            ? $platform->getIntegerTypeDeclarationSQL($column)
            : $platform->getVarcharTypeDeclarationSQL($column);
    }

    public function getName(): string
    {
        return $this->name ?? throw new \LogicException(
            sprintf(
                'Class `%s` cannot be used as primary type; register your own types with %s::registerEnumType() instead.',
                __CLASS__,
                __CLASS__,
            )
        );
    }

    /**
     * @param BackedEnum|null $enum
     */
    public function convertToDatabaseValue(mixed $enum, AbstractPlatform $platform): int|string|null
    {
        if (null === $enum) {
            return null;
        }

        if (!$enum instanceof BackedEnum) {
            throw new InvalidArgumentException(
                sprintf('Expected instance of BackedEnum, got `%s`.', \get_debug_type($enum))
            );
        }

        return $enum->value;
    }

    public function convertToPHPValue(mixed $value, AbstractPlatform $platform): ?BackedEnum
    {
        if (null === $value) {
            return null;
        }

        $value = $this->type->cast($value);

        /** @var BackedEnum $class */
        $class = $this->class;

        return $class::from($value);
    }

    public static function detectEnumType(string $enumClass): BackedEnumType
    {
        $type = (new ReflectionEnum($enumClass))->getBackingType()?->getName();

        return 'int' === $type ? BackedEnumType::INT : BackedEnumType::STRING;
    }

    /**
     * @codeCoverageIgnore
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
