<?php

declare(strict_types=1);

namespace BenTools\Doctrine\NativeEnums\Tests;

use Doctrine\DBAL\Platforms\AbstractPlatform;

final class DoctrinePlatformStub extends AbstractPlatform
{

    public function getCurrentDatabaseExpression(): string
    {
        return '';
    }

    public function getBooleanTypeDeclarationSQL(array $columnDef): string
    {
        return '';
    }

    public function getIntegerTypeDeclarationSQL(array $columnDef): string
    {
        return '';
    }

    public function getBigIntTypeDeclarationSQL(array $columnDef): string
    {
        return '';
    }

    public function getSmallIntTypeDeclarationSQL(array $columnDef): string
    {
        return '';
    }

    // phpcs:disable
    protected function _getCommonIntegerTypeDeclarationSQL(array $columnDef): string
    {
        return '';
    }

    protected function initializeDoctrineTypeMappings(): void
    {
    }

    public function getClobTypeDeclarationSQL(array $field): string
    {
        return '';
    }

    public function getBlobTypeDeclarationSQL(array $field): string
    {
        return '';
    }

    public function getName(): string
    {
        return '';
    }
}
