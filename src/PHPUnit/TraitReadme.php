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

use JBZoo\Markdown\Markdown;

use function JBZoo\PHPUnit\fail;
use function JBZoo\PHPUnit\isFileContains;
use function JBZoo\PHPUnit\isNotEmpty;
use function JBZoo\PHPUnit\success;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitReadme
{
    // See also
    // - https://github.com/badges/shields#specification
    // - https://github.com/badges/poser

    protected string $readmeFile = 'README.md';

    /** @var bool[] */
    protected array $params = [
        // Packagist
        'packagist_latest_stable_version'   => true,
        'packagist_latest_unstable_version' => true,
        'packagist_license'                 => true,
        'packagist_version'                 => true,

        'packagist_dependents' => true,
        'packagist_suggesters' => true,

        'packagist_downloads_total'   => true,
        'packagist_downloads_daily'   => true,
        'packagist_downloads_monthly' => true,

        'packagist_composerlock'  => true,
        'packagist_gitattributes' => true,

        'github_issues'  => true,
        'github_license' => true,
        'github_forks'   => true,
        'github_stars'   => true,
        'github_actions' => true,

        'docker_build' => false,
        'docker_pulls' => false,

        'strict_types' => false,
        'codecov'      => false,
        'scrutinizer'  => false,

        'psalm_coverage' => true,
        'psalm_level'    => true,
        'codacy'         => true,
        'codefactor'     => true,
        'sonarcloud'     => true,
        'coveralls'      => true,
        'circle_ci'      => true,
        'visitors'       => false,
    ];

    /** @var string[] */
    protected array $badgesTemplate = [
        'github_actions',
        'docker_build',
        'codecov',
        'coveralls',
        'psalm_coverage',
        'psalm_level',
        'codefactor',
        'scrutinizer',
        '__BR__',
        'packagist_latest_stable_version',
        'packagist_downloads_total',
        'docker_pulls',
        'packagist_dependents',
        'visitors',
        'github_license',
    ];

    protected string $codacyId = '__SEE_REPO_CONFIG__';

    // ### Test cases ##################################################################################################

    public function testReadmeHeader(): void
    {
        $expectedBadges = [];

        foreach ($this->badgesTemplate as $badgeName) {
            if ($badgeName === '__BR__') {
                $expectedBadges[$badgeName] = "\n";
            } else {
                $testMethod = 'checkBadge' . \str_replace('_', '', \ucwords($badgeName, '_'));

                if (\method_exists($this, $testMethod)) {
                    /** @phpstan-ignore-next-line */
                    $tmpBadge = $this->{$testMethod}();
                    if ($tmpBadge !== null) {
                        $expectedBadges[$badgeName] = "{$tmpBadge}    ";
                    }
                } else {
                    fail("Method not found: '{$testMethod}'");
                }
            }
        }

        $expectedBadgeLine = \implode("\n", [
            $this->getTitle(),
            '',
            \trim(\implode('', \array_filter($expectedBadges))),
            '',
            '',
        ]);

        isFileContains($expectedBadgeLine, PROJECT_ROOT . '/README.md');
    }

    protected function getTitle(): string
    {
        return "# {$this->vendorName} / {$this->packageName}";
    }

    // ### Bages #######################################################################################################

    protected function checkBadgePackagistLatestStableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Stable Version', 'version'));
    }

    protected function checkBadgePackagistLatestUnstableVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Latest Unstable Version', 'v/unstable'));
    }

    protected function checkBadgePackagistDownloadsTotal(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Total Downloads', 'downloads', 'stats'));
    }

    protected function checkBadgePackagistLicense(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('License', 'license'));
    }

    protected function checkBadgePackagistDownloadsMonthly(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Monthly Downloads', 'd/monthly', 'stats'));
    }

    protected function checkBadgePackagistDownloadsDaily(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Daily Downloads', 'd/daily', 'stats'));
    }

    protected function checkBadgePackagistVersion(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'version'));
    }

    protected function checkBadgePackagistComposerlock(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Version', 'composerlock'));
    }

    protected function checkBadgePackagistGitAttributes(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('.gitattributes', 'gitattributes'));
    }

    protected function checkBadgePackagistDependents(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadgePackagist('Dependents', 'dependents', 'dependents?order_by=downloads'),
        );
    }

    protected function checkBadgePackagistSuggesters(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('Suggesters', 'suggesters'));
    }

    protected function checkBadgeCircleCI(): ?string
    {
        return $this->getPreparedBadge($this->getBadgePackagist('CircleCI Build', 'circleci'));
    }

    protected function checkBadgeCoveralls(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Coverage Status',
                'https://coveralls.io/repos/github/__VENDOR_ORIG__/__PACKAGE_ORIG__/badge.svg?branch=master',
                'https://coveralls.io/github/__VENDOR_ORIG__/__PACKAGE_ORIG__?branch=master',
            ),
        );
    }

    protected function checkBadgeCodacy(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Codacy Badge',
                "https://app.codacy.com/project/badge/Grade/{$this->codacyId}",
                'https://www.codacy.com/gh/__VENDOR__/__PACKAGE__',
            ),
        );
    }

    protected function checkBadgePsalmCoverage(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Psalm Coverage',
                'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__/coverage.svg',
                'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__',
            ),
        );
    }

    protected function checkBadgePsalmLevel(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Psalm Level',
                'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__/level.svg',
                'https://shepherd.dev/github/__VENDOR_ORIG__/__PACKAGE_ORIG__',
            ),
        );
    }

    protected function checkBadgeGithubIssues(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'GitHub Issues',
                'https://img.shields.io/github/issues/__VENDOR__/__PACKAGE__',
                'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/issues',
            ),
        );
    }

    protected function checkBadgeGithubForks(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'GitHub Forks',
                'https://img.shields.io/github/forks/__VENDOR__/__PACKAGE__',
                'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/network',
            ),
        );
    }

    protected function checkBadgeGithubStars(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'GitHub Stars',
                'https://img.shields.io/github/stars/__VENDOR__/__PACKAGE__',
                'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/stargazers',
            ),
        );
    }

    protected function checkBadgeGithubLicense(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'GitHub License',
                'https://img.shields.io/github/license/__VENDOR__/__PACKAGE__',
                'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/blob/master/LICENSE',
            ),
        );
    }

    protected function checkBadgeDockerBuild(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Docker Cloud Build Status',
                'https://img.shields.io/docker/cloud/build/__VENDOR__/__PACKAGE__.svg',
                'https://hub.docker.com/r/__VENDOR__/__PACKAGE__',
            ),
        );
    }

    protected function checkBadgeDockerPulls(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Docker Pulls',
                'https://img.shields.io/docker/pulls/__VENDOR__/__PACKAGE__.svg',
                'https://hub.docker.com/r/__VENDOR__/__PACKAGE__',
            ),
        );
    }

    protected function checkBadgeScrutinizer(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Scrutinizer Code Quality',
                'https://scrutinizer-ci.com/g/__VENDOR__/__PACKAGE__/badges/quality-score.png?b=master',
                'https://scrutinizer-ci.com/g/__VENDOR__/__PACKAGE__/?branch=master',
            ),
        );
    }

    protected function checkBadgeCodefactor(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'CodeFactor',
                'https://www.codefactor.io/repository/github/__VENDOR__/__PACKAGE__/badge',
                'https://www.codefactor.io/repository/github/__VENDOR__/__PACKAGE__/issues',
            ),
        );
    }

    protected function checkBadgeSonarcloud(): ?string
    {
        $project = '__VENDOR_ORIG_____PACKAGE_ORIG__';

        return $this->getPreparedBadge(
            $this->getBadge(
                'Quality Gate Status',
                "https://sonarcloud.io/api/project_badges/measure?project={$project}&metric=alert_status",
                "https://sonarcloud.io/dashboard?id={$project}",
            ),
        );
    }

    protected function checkBadgeStrictTypes(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'PHP Strict Types',
                'https://img.shields.io/badge/strict__types-%3D1-brightgreen',
                'https://www.php.net/manual/en/language.types.declarations.php#language.types.declarations.strict',
            ),
        );
    }

    protected function getBadgePackagist(string $name, string $mode, ?string $postfix = null): string
    {
        return $this->getBadge(
            $name,
            "https://poser.pugx.org/__VENDOR__/__PACKAGE__/{$mode}",
            'https://packagist.org/packages/__VENDOR__/__PACKAGE__' . ($postfix !== '' ? "/{$postfix}" : ''),
        );
    }

    protected function checkBadgeCodecov(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'codecov',
                'https://codecov.io/gh/__VENDOR_ORIG__/__PACKAGE_ORIG__/branch/master/graph/badge.svg',
                'https://codecov.io/gh/__VENDOR_ORIG__/__PACKAGE_ORIG__/branch/master',
            ),
        );
    }

    protected function checkBadgePhpVersion(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'PHP Version',
                'https://img.shields.io/packagist/php-v/__VENDOR__/__PACKAGE__',
                'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/blob/master/composer.json',
            ),
        );
    }

    protected function checkBadgeGithubActions(): ?string
    {
        $path = 'https://github.com/__VENDOR_ORIG__/__PACKAGE_ORIG__/actions/workflows';

        return $this->getPreparedBadge(
            $this->getBadge(
                'CI',
                $path . '/main.yml/badge.svg?branch=master',
                $path . '/main.yml?query=branch%3Amaster',
            ),
        );
    }

    protected function checkBadgeVisitors(): ?string
    {
        return $this->getPreparedBadge(
            $this->getBadge(
                'Visitors',
                'https://visitor-badge.glitch.me/badge?page_id=__VENDOR__.__PACKAGE__',
            ),
        );
    }

    // // Tools ////////////////////////////////////////////////////////////////////////////////////////////////////////

    protected function getBadge(string $name, string $svgUrl, string $linkUrl = ''): string
    {
        /** @var string[] $params */
        $params = [
            '__NAME__'         => $name,
            '__SVG_URL__'      => $svgUrl,
            '__SERVICE_URL__'  => $linkUrl,
            '__VENDOR_ORIG__'  => $this->vendorName,
            '__PACKAGE_ORIG__' => $this->packageName,
            '__VENDOR__'       => \strtolower($this->vendorName),
            '__PACKAGE__'      => \strtolower($this->packageName),
        ];

        $result = (string)Markdown::badge('__NAME__', '__SVG_URL__', '__SERVICE_URL__');

        foreach ($params as $key => $value) {
            $result = \str_replace($key, $value, $result);
        }

        return $result;
    }

    protected function getPreparedBadge(string $badge): ?string
    {
        $trace        = \debug_backtrace(\DEBUG_BACKTRACE_IGNORE_ARGS);
        $testCaseName = \str_replace('check_badge_', '', self::splitCamelCase($trace[1]['function']));
        $isEnabled    = $this->params[$testCaseName] ?? null;

        if ($isEnabled === null) {
            return null;
        }

        if (!$isEnabled) {
            success();

            return null;
        }

        return $badge;
    }

    protected static function getReadme(): string
    {
        $content = (string)\file_get_contents(PROJECT_ROOT . '/README.md');
        isNotEmpty($content);

        return $content;
    }

    protected static function splitCamelCase(string $input): string
    {
        $original = $input;

        $output = (string)\preg_replace(['/(?<=[^A-Z])([A-Z])/', '/(?<=[^0-9])([0-9])/'], '_$0', $input);
        $output = (string)\preg_replace('#_{1,}#', '_', $output);

        $output = \trim($output);
        $output = \strtolower($output);

        if ($output === '') {
            return $original;
        }

        return $output;
    }
}
