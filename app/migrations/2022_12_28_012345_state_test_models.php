<?php declare(strict_types=1);

use App\ModelStates\StateManager;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration {
    public function up(): void
    {
        Schema::create('test.test_models', function (Blueprint $table): void {
            $table->increments('id');
            $table->string('message')->nullable();
            $table->timestamps();
        });

        Schema::create('test.state_managers', function (Blueprint $table): void {
            $table->increments('id');
            $table->morphs('stateable');
            $table->string(StateManager::stateColumnName());
            $table->timestamps();
        });
    }
};
