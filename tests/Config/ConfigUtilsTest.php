<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Config;

use Illuminate\Support\Facades\Config;
use MLL\LaravelUtils\Config\ConfigUtils;
use MLL\LaravelUtils\Tests\TestCase;

final class ConfigUtilsTest extends TestCase
{
    private const KEY = 'config_utils_test';

    public function testTryStringReturnsString(): void
    {
        Config::set(self::KEY, 'foo');

        self::assertSame('foo', ConfigUtils::tryString(self::KEY));
    }

    public function testTryStringReturnsNull(): void
    {
        Config::set(self::KEY, null);

        self::assertNull(ConfigUtils::tryString(self::KEY));
    }

    public function testTryStringReturnsNullForMissingKey(): void
    {
        self::assertNull(ConfigUtils::tryString(self::KEY));
    }

    public function testTryStringThrowsOnOtherType(): void
    {
        Config::set(self::KEY, 1);

        $this->expectExceptionObject(new \InvalidArgumentException('Configuration value for key [config_utils_test] must be a string or null, integer given.'));
        ConfigUtils::tryString(self::KEY);
    }
}
