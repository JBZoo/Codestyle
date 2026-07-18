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
use function JBZoo\PHPUnit\isTrue;

trait TraitGithubActions
{
    public static function testGithubActionsWorkflow(): void
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
            'name'        => 'CI',
            'permissions' => [
                'contents' => 'read',
            ],
            'on' => [
                'pull_request' => ['branches' => ['*']],
                'push'         => ['branches' => ['master']],
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

        // #3 Canary (major 8.0): the workflow MUST define a schedule with at least one non-blank string
        // cron and allow workflow_dispatch (null or a config map), but NOT a specific cron literal
        // (per-repo cadence is allowed — e.g. toolbox keeps its tighter '30 */8 * * *'). Verified as
        // presence, then dropped before the structural compare below.
        $triggers = (array)($actual['on'] ?? []);
        $schedule = $triggers['schedule'] ?? [];
        $crons    = \is_array($schedule)
            ? \array_filter(
                $schedule,
                static fn (mixed $entry): bool => \is_array($entry)
                    && \is_string($entry['cron'] ?? null)
                    && $entry['cron'] !== '',
            )
            : [];
        isTrue($crons !== [], 'The workflow must define a schedule with at least one non-blank cron (canary).');

        $dispatchOk = \array_key_exists('workflow_dispatch', $triggers)
            && ($triggers['workflow_dispatch'] === null || \is_array($triggers['workflow_dispatch']));
        isTrue($dispatchOk, 'The workflow must allow workflow_dispatch (null or a config map) for the dispatcher.');
        unset($actual['on']['schedule'], $actual['on']['workflow_dispatch']);

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
     * @psalm-suppress PossiblyUnusedParam
     */
    protected static function checkoutStep(string $jobName): array
    {
        return [
            'name' => 'Checkout code',
            'uses' => 'actions/checkout@v7',
            'with' => ['fetch-depth' => 0],
        ];
    }

    protected static function phpVersions(): array
    {
        return [8.3, 8.4, 8.5];
    }

    /**
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     * @phan-suppress PhanUnusedProtectedNoOverrideMethodParameter
     * @psalm-suppress PossiblyUnusedParam
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
            'uses'              => 'actions/upload-artifact@v7',
            'continue-on-error' => true,
            'with'              => ['name' => $stepName, 'path' => 'build/', 'overwrite' => true],
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
}
