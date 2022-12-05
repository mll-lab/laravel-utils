<?php declare(strict_types=1);

use Rector\CodeQuality\Rector\Concat\JoinStringConcatRector;
use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(SetList::CODE_QUALITY);
    $rectorConfig->import(SetList::PHP_71);

    $rectorConfig->skip([
        // skip csv test file to keep `\r` and `\n` for readability
        JoinStringConcatRector::class => [
            // single file
            __DIR__ . '/tests/Unit/CSVArrayTest.php',
        ],
    ]);

    // paths to refactor; solid alternative to CLI arguments
    $rectorConfig->paths([__DIR__ . '/src', __DIR__ . '/tests']);

    // Path to PHPStan with extensions, that PHPStan in Rector uses to determine types
    $rectorConfig->phpstanConfig(__DIR__ . '/phpstan.neon');
};
