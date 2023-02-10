<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Database;

interface ConditionalMigration
{
    /**
     * Should the migration run?
     */
    public function shouldRun(): bool;
}
