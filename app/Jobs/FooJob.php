<?php declare(strict_types=1);

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use MLL\LaravelUtils\Queue\ExponentialBackoff;

final class FooJob implements ShouldQueue
{
    use ExponentialBackoff;
}
