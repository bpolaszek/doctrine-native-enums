<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Tests\Sample\Entity;

use BenTools\Doctrine\NativeEnums\Tests\IntegerEnumStub;
use BenTools\Doctrine\NativeEnums\Tests\StringEnumStub;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
class Foo
{
    #[ORM\Id]
    #[ORM\Column]
    #[ORM\GeneratedValue]
    public int $id;

    #[ORM\Column(type: 'foos')]
    public StringEnumStub $name;

    #[ORM\Column(type: IntegerEnumStub::class)]
    public IntegerEnumStub $number;
}
