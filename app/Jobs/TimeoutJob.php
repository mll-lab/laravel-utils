<?php declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

final class TimeoutJob implements ShouldQueue
{
    use InteractsWithQueue;
    use Queueable;

    public int $timeout = 300;

    public function handle(): void {}
}
