<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Tests\Bundle;

use BenTools\Doctrine\NativeEnums\Tests\IntegerEnumStub;
use BenTools\Doctrine\NativeEnums\Tests\Sample\Entity\Foo;
use BenTools\Doctrine\NativeEnums\Tests\StringEnumStub;

beforeAll(function () {
    create_database();
    create_schema();
});

afterAll(function () {
    drop_database();
});

it('persists enums to database', function () {
    $foo = new Foo();
    $foo->name = StringEnumStub::BAR;
    $foo->number = IntegerEnumStub::ONE;
    save($foo);

    entityManager()->clear();

    /** @var Foo $bar */
    $bar = repository(Foo::class)->findAll()[0];
    expect($bar)->not()->toBe($foo);
    expect($bar->name)->toBe(StringEnumStub::BAR);
    expect($bar->number)->toBe(IntegerEnumStub::ONE);
});
