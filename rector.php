<?php declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\PHPUnit\Set\PHPUnitSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->sets([
        SetList::CODE_QUALITY,
        SetList::PHP_71,
        SetList::PHP_72,
        SetList::PHP_73,
        SetList::PHP_74,
        SetList::PHP_80,
        SetList::PHP_81,
        SetList::PHP_82,
        PHPUnitSetList::PHPUNIT_40,
        PHPUnitSetList::PHPUNIT_50,
        PHPUnitSetList::PHPUNIT_60,
        PHPUnitSetList::PHPUNIT_70,
        PHPUnitSetList::PHPUNIT_80,
        PHPUnitSetList::PHPUNIT_90,
        PHPUnitSetList::PHPUNIT_100,
        PHPUnitSetList::PHPUNIT_110,
        PHPUnitSetList::PHPUNIT_CODE_QUALITY,
        PHPUnitSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
    $rectorConfig->importNames();
    $rectorConfig->importShortClasses(false);
    $rectorConfig->rule(Rector\PHPUnit\CodeQuality\Rector\Class_\PreferPHPUnitSelfCallRector::class);
    $rectorConfig->skip([
        // skip csv test file to keep `\r` and `\n` for readability
        Rector\CodeQuality\Rector\Concat\JoinStringConcatRector::class => [
            // single file
            __DIR__ . '/tests/Unit/CSVArrayTest.php',
        ],
        // Unsafe with typed properties
        Rector\CodeQuality\Rector\Isset_\IssetOnPropertyObjectToPropertyExistsRector::class,
    ]);
    $rectorConfig->paths([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);
    $rectorConfig->bootstrapFiles([
        // Rector uses PHPStan internally, which in turn requires Larastan to be set up correctly
        __DIR__ . '/vendor/larastan/larastan/bootstrap.php',
    ]);
    $rectorConfig->phpstanConfigs([
        __DIR__ . '/phpstan.neon',
        __DIR__ . '/vendor/larastan/larastan/extension.neon',
    ]);
};
