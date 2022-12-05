<?php declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use MLL\LaravelUtils\Tests\Database\MaxNo;

return new class() extends Migration {
    public function up(): void
    {
        MaxNo::createTable();
    }
};
