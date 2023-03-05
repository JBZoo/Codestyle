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
use function JBZoo\PHPUnit\is;
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
        $phpVersions    = [8.1, 8.2];
        $phpExtensions  = 'ast';
        $setupPhpAction = 'shivammathur/setup-php@v2';
        $expectedOs     = 'ubuntu-latest';

        // General Steps
        $checkoutStep = [
            'name' => 'Checkout code',
            'uses' => 'actions/checkout@v3',
            'with' => ['fetch-depth' => 0],
        ];
        $buildProjectStep = [
            'name' => 'Build the Project',
            'run'  => 'make update --no-print-directory',
        ];

        // Expected
        $expected = [
            'name' => 'CI',
            'on'   => [
                'pull_request' => ['branches' => ['*']],
                'push'         => ['branches' => ['master']],
                'schedule'     => [['cron' => "{$this->gaScheduleMinute} */8 * * *"]],
            ],

            'env' => [
                'COLUMNS'      => 120,
                'TERM_PROGRAM' => 'Hyper',
            ],

            'jobs' => [
                'phpunit' => [
                    'name'    => 'PHPUnit',
                    'runs-on' => $expectedOs,
                    'env'     => ['JBZOO_COMPOSER_UPDATE_FLAGS' => '${{ matrix.composer_flags }}'],

                    'strategy' => [
                        'matrix' => [
                            'php-version'    => $phpVersions,
                            'coverage'       => ['xdebug', 'none'],
                            'composer_flags' => ['--prefer-lowest', ''],
                        ],
                    ],
                    'steps' => [
                        $checkoutStep,
                        [
                            'name' => 'Setup PHP',
                            'uses' => $setupPhpAction,
                            'with' => [
                                'php-version' => '${{ matrix.php-version }}',
                                'coverage'    => '${{ matrix.coverage }}',
                                'tools'       => 'composer',
                                'extensions'  => $phpExtensions,
                            ],
                        ],
                        $buildProjectStep,
                        [
                            'name' => '🧪 PHPUnit Tests',
                            'run'  => 'make test --no-print-directory',
                        ],
                        [
                            'name' => 'Uploading coverage to coveralls',
                            'if'   => "\${{ matrix.coverage == 'xdebug' }}",
                            'env'  => ['COVERALLS_REPO_TOKEN' => '${{ secrets.GITHUB_TOKEN }}'],
                            'run'  => 'make report-coveralls --no-print-directory || true',
                        ],
                        $this->uploadArtifactsStep('PHPUnit - ${{ matrix.php-version }} - ${{ matrix.coverage }}'),
                    ],
                ],
                'linters' => [
                    'name'     => 'Linters',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => $phpVersions]],
                    'steps'    => [
                        $checkoutStep,
                        [
                            'name' => 'Setup PHP',
                            'uses' => $setupPhpAction,
                            'with' => [
                                'php-version' => '${{ matrix.php-version }}',
                                'coverage'    => 'none',
                                'tools'       => 'composer',
                                'extensions'  => $phpExtensions,
                            ],
                        ],
                        $buildProjectStep,
                        [
                            'name' => '👍 Code Quality',
                            'run'  => 'make codestyle --no-print-directory',
                        ],
                        $this->uploadArtifactsStep('Linters - ${{ matrix.php-version }}'),
                    ],
                ],
                'report' => [
                    'name'     => 'Reports',
                    'runs-on'  => $expectedOs,
                    'strategy' => ['matrix' => ['php-version' => $phpVersions]],
                    'steps'    => [
                        $checkoutStep,
                        [
                            'name' => 'Setup PHP',
                            'uses' => $setupPhpAction,
                            'with' => [
                                'php-version' => '${{ matrix.php-version }}',
                                'coverage'    => 'xdebug',
                                'tools'       => 'composer',
                                'extensions'  => $phpExtensions,
                            ],
                        ],
                        $buildProjectStep,
                        [
                            'name' => '📝 Build Reports',
                            'run'  => 'make report-all --no-print-directory',
                        ],
                        $this->uploadArtifactsStep('Reports - ${{ matrix.php-version }}'),
                    ],
                ],
            ],
        ];

        $actualYml    = (string)yml($actual);
        $expectedYaml = (string)yml($expected);
        isSame($expectedYaml, $actualYml);

        is($expected, $actual, $expectedYaml);
        isSame($expected, $actual);
    }

    /**
     * @suppress PhanPluginPossiblyStaticPrivateMethod
     */
    private function uploadArtifactsStep(string $stepName): array
    {
        return [
            'name'              => 'Upload Artifacts',
            'uses'              => 'actions/upload-artifact@v3',
            'continue-on-error' => true,
            'with'              => ['name' => $stepName, 'path' => 'build/'],
        ];
    }
}
