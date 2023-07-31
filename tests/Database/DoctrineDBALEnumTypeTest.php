<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Database;

use Doctrine\DBAL\Platforms\MySQLPlatform;
use Doctrine\DBAL\Types\Type;
use MLL\LaravelUtils\Database\DoctrineDBALEnumType;
use MLL\LaravelUtils\Tests\DBTestCase;

final class DoctrineDBALEnumTypeTest extends DBTestCase
{
    public function testIsRegistered(): void
    {
        self::assertInstanceOf(
            DoctrineDBALEnumType::class,
            Type::getType(DoctrineDBALEnumType::NAME)
        );
    }

    public function testGetSQLDeclaration(): void
    {
        $enumType = Type::getType(DoctrineDBALEnumType::NAME);

        self::assertSame(
            "ENUM('foo','bar')",
            $enumType->getSQLDeclaration(
                ['allowed' => ['foo', 'bar']],
                new MySQLPlatform()
            )
        );
    }
}
