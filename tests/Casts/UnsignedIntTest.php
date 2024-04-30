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

        $model->setRawAttribute('unsigned_int', -1);
        self::assertSame(0, $model->unsigned_int);
    }
}
