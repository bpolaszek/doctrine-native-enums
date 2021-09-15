<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Tests\Unit;

use BackedEnum;
use BenTools\Doctrine\NativeEnums\Tests\DoctrinePlatformStub;
use BenTools\Doctrine\NativeEnums\Tests\IntegerEnumStub;
use BenTools\Doctrine\NativeEnums\Tests\StringEnumStub;
use BenTools\Doctrine\NativeEnums\Tests\StandardEnumStub;
use BenTools\Doctrine\NativeEnums\Type\NativeEnum;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;
use LogicException;
use stdClass;

it('registers a new Enum by its class name', function () {
    NativeEnum::registerEnumType(StringEnumStub::class);
    $type = Type::getType(StringEnumStub::class);
    expect($type)->toBeInstanceOf(NativeEnum::class);
});

it('registers a new Enum with a custom name', function () {
    clear_type_registry();
    NativeEnum::registerEnumType('foobar', StringEnumStub::class);
    expect(Type::hasType(StringEnumStub::class))->toBeFalse();
    expect(Type::hasType('foobar'))->toBeTrue();
    $type = NativeEnum::getType('foobar');
    expect($type)->toBeInstanceOf(NativeEnum::class);
});

it('yells when used standalone', function () {
    clear_type_registry();
    Type::addType('native_enum', NativeEnum::class);
    Type::getType('native_enum')->getName();
})
    ->throws(LogicException::class);

it('yells when trying to register a non-backed enum', function ($input) {
    clear_type_registry();
    NativeEnum::registerEnumType($input);
})
    ->with([
        StandardEnumStub::class,
        stdClass::class,
        'foo',
    ])
    ->throws(InvalidArgumentException::class);

test('PHP -> DB => converts a backed enum to a scalar value', function (string $type, ?BackedEnum $input, string|int|null $expected) {
    clear_type_registry();
    NativeEnum::registerEnumType($type);
    expect(Type::getType($type)->convertToDatabaseValue($input, new DoctrinePlatformStub()))->toBe($expected);
})->with([
    [StringEnumStub::class, StringEnumStub::BAR, 'bar'],
    [IntegerEnumStub::class, IntegerEnumStub::ONE, 1],
    [StringEnumStub::class, null, null],
    [IntegerEnumStub::class, null, null],
]);

test('PHP -> DB conversion yells when trying to convert a non-backed-enum', function ($value) {
    clear_type_registry();
    NativeEnum::registerEnumType(StringEnumStub::class);
    Type::getType(StringEnumStub::class)->convertToDatabaseValue($value, new DoctrinePlatformStub());
})
    ->with([
        StandardEnumStub::class,
        stdClass::class,
        'foo',
    ])
    ->throws(InvalidArgumentException::class);

test('DB -> PHP => converts a scalar value to an enum', function (string $type, string|int|null $input, ?BackedEnum $expected) {
    clear_type_registry();
    NativeEnum::registerEnumType($type);
    expect(Type::getType($type)->convertToPHPValue($input, new DoctrinePlatformStub()))->toBe($expected);
})->with([
    [StringEnumStub::class, 'bar', StringEnumStub::BAR],
    [IntegerEnumStub::class, 1, IntegerEnumStub::ONE],
    [StringEnumStub::class, null, null],
    [IntegerEnumStub::class, null, null],
]);

it('generates the appropriate SQL definition', function (string $enumClass, string $expectedMethod) {
    clear_type_registry();
    $platform = \Mockery::mock(AbstractPlatform::class);
    NativeEnum::registerEnumType($enumClass);
    $platform->shouldReceive($expectedMethod)->andReturn('');
    Type::getType($enumClass)->getSQLDeclaration([], $platform);
})->with([
    [StringEnumStub::class, 'getVarcharTypeDeclarationSQL'],
    [IntegerEnumStub::class, 'getIntegerTypeDeclarationSQL'],
]);
