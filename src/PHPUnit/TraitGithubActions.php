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
        $actual = yml(PROJECT_ROOT . '/.github/workflows/main.yml')->getArrayCopy();

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

            'env' => [
                'COLUMNS'      => 120,
                'TERM_PROGRAM' => 'Hyper',
            ],

            'jobs' => [
                'phpunit' => [
                    'name'     => 'PHPUnit',
                    'runs-on'  => $expectedOs,
                    'env'      => ['JBZOO_COMPOSER_UPDATE_FLAGS' => '${{ matrix.composer_flags }}'],
                    'strategy' => [
                        'matrix' => [
                            'php-version'    => self::phpVersions(),
                            'coverage'       => ['xdebug', 'none'],
                            'composer_flags' => ['--prefer-lowest', ''],
                        ],
                    ],
                    'steps' => [
                        self::checkoutStep('phpunit'),
                        self::setupPhpStep('phpunit'),
                        self::buildStep('phpunit'),
                        self::stepBeforeTests(),
                        [
                            'name' => '🧪 PHPUnit Tests',
                            'run'  => 'make test --no-print-directory',
                        ],
                        self::stepAfterTests(),
                        [
                            'name'              => 'Uploading coverage to coveralls',
                            'if'                => '${{ matrix.coverage == \'xdebug\' }}',
                            'continue-on-error' => true,
                            'env'               => ['COVERALLS_REPO_TOKEN' => '${{ secrets.GITHUB_TOKEN }}'],
                            'run'               => 'make report-coveralls --no-print-directory || true',
                        ],
                        self::uploadArtifactsStep('PHPUnit - ${{ matrix.php-version }} - ${{ matrix.coverage }}'),
                    ],
                ],
                'linters' => [
                    'name'     => 'Linters',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => self::phpVersions()]],
                    'steps'    => [
                        self::checkoutStep('linters'),
                        self::setupPhpStep('linters'),
                        self::buildStep('linters'),
                        [
                            'name' => '👍 Code Quality',
                            'run'  => 'make codestyle --no-print-directory',
                        ],
                        self::uploadArtifactsStep('Linters - ${{ matrix.php-version }}'),
                    ],
                ],
                'report' => [
                    'name'     => 'Reports',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => self::phpVersions()]],
                    'steps'    => [
                        self::checkoutStep('report'),
                        self::setupPhpStep('report'),
                        self::buildStep('report'),
                        [
                            'name' => '📝 Build Reports',
                            'run'  => 'make report-all --no-print-directory',
                        ],
                        self::uploadArtifactsStep('Reports - ${{ matrix.php-version }}'),
                    ],
                ],
            ],
        ];

        $expectedYaml = self::toYaml($expected);
        $actualYaml   = self::toYaml($actual);

        isSame($expectedYaml, $actualYaml);
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
        return [8.1, 8.2];
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
            'name' => 'Upload Artifacts',
            'uses' => 'actions/upload-artifact@v3',

            'continue-on-error' => true,

            'with' => ['name' => $stepName, 'path' => 'build/'],
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
