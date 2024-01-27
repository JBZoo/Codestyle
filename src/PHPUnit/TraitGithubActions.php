<?php

/**
 * JBZoo Toolbox - Codestyle.
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @see        https://github.com/JBZoo/Codestyle
 */

declare(strict_types=1);

namespace JBZoo\Codestyle\PHPUnit;

use function JBZoo\Data\yml;
use function JBZoo\PHPUnit\isSame;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitGithubActions
{
    public function testGithubActionsWorkflow(): void
    {
        $mailYmlPath = PROJECT_ROOT . '/.github/workflows/main.yml';
        $actual      = yml($mailYmlPath)->getArrayCopy();

        isSame(120, $actual['env']['COLUMNS']);
        isSame('Hyper', $actual['env']['TERM_PROGRAM']);
        unset($actual['env']);

        // General Parameters
        $expectedOs = 'ubuntu-latest';

        // Expected
        $expected = [
            'name' => 'CI',
            'on'   => [
                'pull_request' => ['branches' => ['*']],
                'push'         => ['branches' => ['master']],
                'schedule'     => [['cron' => $this->getScheduleMinute()]],
            ],

            'jobs' => [
                'phpunit' => [
                    'name'     => 'PHPUnit',
                    'runs-on'  => $expectedOs,
                    'env'      => ['JBZOO_COMPOSER_UPDATE_FLAGS' => '${{ matrix.composer_flags }}'],
                    'strategy' => [
                        'matrix' => [
                            'php-version'    => static::phpVersions(),
                            'coverage'       => ['xdebug', 'none'],
                            'composer_flags' => ['--prefer-lowest', ''],
                        ],
                    ],
                    'steps' => [
                        static::checkoutStep('phpunit'),
                        static::setupPhpStep('phpunit'),
                        static::buildStep('phpunit'),
                        static::stepBeforeTests(),
                        [
                            'name' => '🧪 PHPUnit Tests',
                            'run'  => 'make test --no-print-directory',
                        ],
                        static::stepAfterTests(),
                        [
                            'name'              => 'Uploading coverage to coveralls',
                            'if'                => '${{ matrix.coverage == \'xdebug\' }}',
                            'continue-on-error' => true,
                            'env'               => ['COVERALLS_REPO_TOKEN' => '${{ secrets.GITHUB_TOKEN }}'],
                            'run'               => 'make report-coveralls --no-print-directory || true',
                        ],
                        static::uploadArtifactsStep('PHPUnit - ${{ matrix.php-version }} - ${{ matrix.coverage }}'),
                    ],
                ],
                'linters' => [
                    'name'     => 'Linters',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => static::phpVersions()]],
                    'steps'    => [
                        static::checkoutStep('linters'),
                        static::setupPhpStep('linters'),
                        static::buildStep('linters'),
                        [
                            'name' => '👍 Code Quality',
                            'run'  => 'make codestyle --no-print-directory',
                        ],
                        static::uploadArtifactsStep('Linters - ${{ matrix.php-version }}'),
                    ],
                ],
                'report' => [
                    'name'     => 'Reports',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => static::phpVersions()]],
                    'steps'    => [
                        static::checkoutStep('report'),
                        static::setupPhpStep('report'),
                        static::buildStep('report'),
                        [
                            'name' => '📝 Build Reports',
                            'run'  => 'make report-all --no-print-directory',
                        ],
                        static::uploadArtifactsStep('Reports - ${{ matrix.php-version }}'),
                    ],
                ],
            ],
        ];

        $expectedYaml = self::toYaml($expected);
        $actualYaml   = self::toYaml($actual);

        if (!\str_contains($actualYaml, $expectedYaml)) {
            isSame(
                $expectedYaml,
                $actualYaml,
                \implode("\n", [
                    'Expected Yaml file:',
                    "See: {$mailYmlPath}",
                    '----------------------------------------',
                    $expectedYaml,
                    '----------------------------------------',
                ]),
            );
        }
    }

    protected function getScheduleMinute(): string
    {
        return self::stringToNumber($this->packageName, 59) . ' */8 * * *';
    }

    protected static function stepBeforeTests(): ?array
    {
        return null;
    }

    protected static function stepAfterTests(): ?array
    {
        return null;
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phan-suppress PhanUnusedProtectedNoOverrideMethodParameter
     */
    protected static function checkoutStep(string $jobName): array
    {
        return [
            'name' => 'Checkout code',
            'uses' => 'actions/checkout@v3',
            'with' => ['fetch-depth' => 0],
        ];
    }

    protected static function phpVersions(): array
    {
        return [8.1, 8.2, 8.3];
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phan-suppress PhanUnusedProtectedNoOverrideMethodParameter
     */
    protected static function buildStep(string $jobName): array
    {
        return [
            'name' => 'Build the Project',
            'run'  => 'make update --no-print-directory',
        ];
    }

    protected static function setupPhpStep(string $jobName): array
    {
        $coverage = 'none';
        if ($jobName === 'phpunit') {
            $coverage = '${{ matrix.coverage }}';
        } elseif ($jobName === 'report') {
            $coverage = 'xdebug';
        }

        return [
            'name' => 'Setup PHP',
            'uses' => 'shivammathur/setup-php@v2',
            'with' => [
                'php-version' => '${{ matrix.php-version }}',
                'coverage'    => $coverage,
                'tools'       => 'composer',
                'extensions'  => 'ast',
            ],
        ];
    }

    protected static function uploadArtifactsStep(string $stepName): array
    {
        return [
            'name'              => 'Upload Artifacts',
            'uses'              => 'actions/upload-artifact@v3',
            'continue-on-error' => true,
            'with'              => ['name' => $stepName, 'path' => 'build/'],
        ];
    }

    private static function normalizeData(array $data): array
    {
        foreach ($data['jobs'] as $jobName => $job) {
            foreach ($job['steps'] as $stepIndex => $step) {
                if ($step === null) {
                    unset($data['jobs'][$jobName]['steps'][$stepIndex]);
                }
            }

            $data['jobs'][$jobName]['steps'] = \array_values($data['jobs'][$jobName]['steps']);
        }

        return $data;
    }

    private static function toYaml(array $data): string
    {
        return (string)yml(self::normalizeData($data));
    }

    private static function stringToNumber(string $string, int $maxNumber): int
    {
        $hash   = \md5($string);
        $substr = \substr($hash, 0, 8);
        $number = \hexdec($substr);

        return $number % ($maxNumber + 1);
    }
}
