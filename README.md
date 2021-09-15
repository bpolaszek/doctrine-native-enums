# Doctrine Native Enums

This library provides first-class support to [PHP Enums](https://wiki.php.net/rfc/enumerations), introduced in PHP 8.1,
within your Doctrine entities.

## Installation

```bash
composer require bentools/doctrine-native-enums
```

## Usage

This library only works with [Backed enums](https://wiki.php.net/rfc/enumerations#backed_enums).

### In a Symfony project

#### 1. Declare the bundle.

```php
// config/bundles.php

return [
    // ...
    BenTools\Doctrine\NativeEnums\Bundle\DoctrineNativeEnumsBundle::class => ['all' => true],
];
```

#### 2. Register enums in your configuration.

```yaml
# config/packages/doctrine_native_enums.yaml

doctrine_native_enums:
  enum_types:
    App\Entity\StatusEnum: ~
    #App\Entity\StatusEnum: status # Alternatively, if you want your type to be named "status"
```

#### 3. Use them in your entities.
```php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
final class Book
{
    #[
        ORM\Id,
        ORM\Column(unique: true),
        ORM\GeneratedValue(strategy: 'AUTO'),
    ]
    public int $id;

    #[ORM\Column]
    public string $name;

    #[ORM\Column(type: StatusEnum::class)]
    public StatusEnum $status;
}
```

### In other projects using Doctrine

```php
use App\Entity\StatusEnum;
use BenTools\Doctrine\NativeEnums\Type\NativeEnum;
use Doctrine\DBAL\Types\Type;

NativeEnum::registerEnumType(StatusEnum::class);
// NativeEnum::registerEnumType('status', StatusEnum::class); // Alternatively, if you want your type to be named "status"
```

## Tests

```bash
php vendor/bin/pest
```

## License

MIT.
