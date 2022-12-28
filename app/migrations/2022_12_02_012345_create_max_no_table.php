<?php declare(strict_types=1);

use App\Database\MaxNo;
use Illuminate\Database\Migrations\Migration;

return new class() extends Migration {
    public function up(): void
    {
        MaxNo::createTable();
    }
};
