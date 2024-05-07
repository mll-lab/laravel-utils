<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\Casts;

use MLL\LaravelUtils\Tests\TestCase;

final class UnsignedIntTest extends TestCase
{
    public function testCast(): void
    {
        $model = new CastsModel();
        self::assertNull($model->unsigned_int);

        $model->unsigned_int = -1;
        self::assertSame(0, $model->getRawAttribute('unsigned_int'));

        --$model->unsigned_int;
        self::assertSame(0, $model->getRawAttribute('unsigned_int'));

        $model->unsigned_int -= 2;
        self::assertSame(0, $model->getRawAttribute('unsigned_int'));

        $model->unsigned_int = 2;
        self::assertSame(2, $model->getRawAttribute('unsigned_int'));

        $model->unsigned_int = 2.4999; // @phpstan-ignore-line should round floats
        self::assertSame(2, $model->getRawAttribute('unsigned_int'));

        $model->unsigned_int = 2.5001; // @phpstan-ignore-line should round floats
        self::assertSame(3, $model->getRawAttribute('unsigned_int'));

        $model->setRawAttribute('unsigned_int', -1);
        self::assertSame(0, $model->unsigned_int);

        $model->setRawAttribute('unsigned_int', 1.1);
        self::assertSame(1, $model->unsigned_int);

        $this->expectExceptionObject(new \RuntimeException('Expected int, got string: "foo".'));
        $model->unsigned_int = 'foo'; // @phpstan-ignore-line intentionally wrong
    }
}
