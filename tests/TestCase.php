<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests;

use MLL\LaravelUtils\LaravelUtilsServiceProvider;
use MLL\LaravelUtils\Mail\MailServiceProvider;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Set when not in setUp.
     *
     * @var \Illuminate\Foundation\Application
     */
    // @phpstan-ignore-next-line
    protected $app;

    /** @return array<int, class-string<\Illuminate\Support\ServiceProvider>> */
    protected function getPackageProviders($app): array
    {
        return [
            LaravelUtilsServiceProvider::class,
            MailServiceProvider::class,
        ];
    }
}
