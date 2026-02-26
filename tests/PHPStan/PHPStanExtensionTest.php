<?php declare(strict_types=1);

namespace MLL\LaravelUtils\Tests\PHPStan;

use PHPStan\Analyser\Analyser;
use PHPStan\Analyser\Error;
use PHPStan\File\FileHelper;
use PHPStan\Testing\PHPStanTestCase;
use PHPUnit\Framework\Attributes\DataProvider;

/**
 * Built like https://github.com/larastan/larastan/blob/e01fd6ad60c659b735816c21a14df0a3cbca7dbe/tests/Integration/IntegrationTest.php.
 */
final class PHPStanExtensionTest extends PHPStanTestCase
{
    /** @return iterable<array{0: string, 1?: array<int, array<int, string>>}> */
    public static function dataIntegrationTests(): iterable
    {
        self::getContainer();

        yield [__DIR__ . '/data/builder.php', [
            6 => ['Calling Illuminate\Database\Eloquent\Builder::create() (as App\Models\User::create()) is forbidden, creating or filling models through arrays prevents static validation from working.'],
            7 => ['Calling Illuminate\Database\Eloquent\Builder::create() (as App\Models\User::create()) is forbidden, creating or filling models through arrays prevents static validation from working.'],
        ]];

        yield [__DIR__ . '/data/factory.php', [
            6 => ['Calling Illuminate\Database\Eloquent\Factories\Factory::createOne() (as Database\Factories\UserFactory::createOne()) is forbidden, creating or filling models through arrays prevents static validation from working.'],
            7 => ['Calling Illuminate\Database\Eloquent\Factories\Factory::createOne() (as Database\Factories\UserFactory::createOne()) is forbidden, creating or filling models through arrays prevents static validation from working.'],
        ]];

        yield [__DIR__ . '/data/functions.php', [
            3 => ['Calling optional() is forbidden, it undermines type safety.'],
            4 => ['Calling getenv() is forbidden, it does not consider the .env file.'],
        ]];

        yield [__DIR__ . '/data/model.php', [
            6 => ['Calling Illuminate\Database\Eloquent\Model::update() (as App\Models\User::update()) is forbidden, it assigns attributes through an array and is not type safe, without parameters it is like save().'],
            7 => ['Calling Illuminate\Database\Eloquent\Model::update() (as App\Models\User::update()) is forbidden, it assigns attributes through an array and is not type safe, without parameters it is like save().'],
        ]];

        yield [__DIR__ . '/data/collection.php', [
            9 => ['Calling Illuminate\Support\Collection::keyBy() is forbidden, because string keys are not type safe.'],
            10 => ['Calling Illuminate\Support\Collection::sortBy() is forbidden, because string keys are not type safe.'],
            11 => ['Calling Illuminate\Support\Collection::sortByDesc() is forbidden, because string keys are not type safe.'],
            12 => ['Calling Illuminate\Support\Collection::groupBy() is forbidden, because string keys are not type safe.'],
            13 => ['Calling Illuminate\Support\Collection::firstWhere() is forbidden, because string keys are not type safe.'],
        ]];
    }

    /** @param array<int, array<int, string>> $expectedErrors */
    #[DataProvider('dataIntegrationTests')]
    public function testIntegration(string $file, ?array $expectedErrors = null): void
    {
        $errors = $this->runAnalyse($file);

        if ($expectedErrors === null) {
            $this->assertNoErrors($errors);
        } else {
            if (count($expectedErrors) > 0) {
                self::assertNotEmpty($errors);
            }

            $this->assertSameErrorMessages($expectedErrors, $errors);
        }
    }

    /**
     * @see https://github.com/phpstan/phpstan-src/blob/c9772621c0bd6eab7e02fdaa03714bea239b372d/tests/PHPStan/Analyser/AnalyserIntegrationTest.php#L604-L622
     * @see https://github.com/phpstan/phpstan/discussions/6888#discussioncomment-2423613
     *
     * @param string[]|null $allAnalysedFiles
     *
     * @throws \Throwable
     *
     * @return Error[]
     */
    private function runAnalyse(string $file, ?array $allAnalysedFiles = null): array
    {
        $file = $this->getFileHelper()->normalizePath($file);

        /** @var Analyser $analyser */
        $analyser = self::getContainer()->getByType(Analyser::class); // @phpstan-ignore-line

        /** @var FileHelper $fileHelper */
        $fileHelper = self::getContainer()->getByType(FileHelper::class);

        $errors = $analyser->analyse([$file], null, null, true, $allAnalysedFiles)->getErrors(); // @phpstan-ignore-line

        foreach ($errors as $error) {
            self::assertSame($fileHelper->normalizePath($file), $error->getFilePath());
        }

        return $errors;
    }

    /**
     *  @param array<int, array<int, string>> $expectedErrors
     *  @param Error[]                        $errors
     */
    private function assertSameErrorMessages(array $expectedErrors, array $errors): void
    {
        foreach ($errors as $error) {
            $errorLine = $error->getLine() ?? 0;

            self::assertArrayHasKey($errorLine, $expectedErrors);
            self::assertContains($error->getMessage(), $expectedErrors[$errorLine], sprintf("Unexpected error \"%s\" at line %d.\n\nExpected \"%s\"", $error->getMessage(), $errorLine, implode("\n\t", $expectedErrors[$errorLine])));
        }
    }

    /** @return string[] */
    public static function getAdditionalConfigFiles(): array
    {
        return [
            __DIR__ . '/../../vendor/larastan/larastan/extension.neon',
            __DIR__ . '/../../vendor/spaze/phpstan-disallowed-calls/extension.neon',
            __DIR__ . '/../../rules.neon',
        ];
    }
}
