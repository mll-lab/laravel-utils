<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Allows the creation of incrementing IDs without actually using autoincrement.
 */
abstract class Autoincrement
{
    /** Use this method in a migration to create the backing table. */
    public static function createTable(): void
    {
        $name = static::name();
        Schema::create($name, static function (Blueprint $table) use ($name): void {
            $table->engine = 'InnoDB';
            $table->unsignedBigInteger($name);
        });
    }

    /**
     * Set the counter to the given value.
     *
     * Set to 0 if you want @see self::next() to start from 1 again.
     */
    public static function set(int $value): void
    {
        $name = static::name();
        $builder = DB::table($name);
        $builder->delete();
        $builder->insert([$name => $value]);
    }

    /**
     * Return the next value in the sequence, e.g. 1, 2, 3.
     *
     * The usage of a transaction ensures that no two callers ever receive the same value.
     */
    public static function next(): int
    {
        return DB::transaction(static function (): int {
            $name = static::name();
            $builder = DB::table($name);

            $current = $builder
                ->lockForUpdate()
                ->max($name);
            assert(is_int($current) || $current === null);

            $next = ($current ?? 0) + 1;

            if ($current === null) {
                $builder->insert([$name => $next]);
            } else {
                $builder->update([$name => $next]);
            }

            return $next;
        });
    }

    /** Name of the table/column, must be unique in the used database. */
    abstract protected static function name(): string;
}
