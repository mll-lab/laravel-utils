<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

/** Allow calling `->change()` on an enum column definition when using migrations. */
class DoctrineDBALEnumType extends Type
{
    final public const NAME = 'enum';

    public function getSQLDeclaration(array $column, AbstractPlatform $platform): string
    {
        /** @var array{allowed: array<string>} $column */
        $values = implode(
            ',',
            array_map(
                fn (string $value): string => "'{$value}'",
                $column['allowed'],
            ),
        );

        return "ENUM({$values})";
    }

    public function getName(): string
    {
        return self::NAME;
    }

    public function getMappedDatabaseTypes(AbstractPlatform $platform): array
    {
        return [self::NAME];
    }
}
