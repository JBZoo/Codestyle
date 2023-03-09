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

use JBZoo\PHPUnit\PHPUnit;
use JBZoo\Utils\Cli;
use JBZoo\Utils\Env;

use function JBZoo\PHPUnit\isPhpStorm;
use function JBZoo\PHPUnit\skip;
use function JBZoo\PHPUnit\success;

abstract class AbstractPhpStormProxyTest extends PHPUnit
{
    public function testPhpCsFixerFix(): void
    {
        $this->runToolViaMakefile('test-phpcsfixer-fix');
    }

    /**
     * @depends testPhpCsFixerFix
     */
    public function testPhpCsFixerCheck(): void
    {
        $this->runToolViaMakefile('test-phpcsfixer-teamcity');
    }

    /**
     * @depends testPhpCsFixerCheck
     */
    public function testPhpCodeSniffer(): void
    {
        $this->runToolViaMakefile('test-phpcs-teamcity');
    }

    public function testPhpMessDetector(): void
    {
        $this->runToolViaMakefile('test-phpmd-teamcity');
    }

    public function testPhpMagicNumbers(): void
    {
        $this->runToolViaMakefile('test-phpmnd-teamcity');
    }

    public function testPhpStan(): void
    {
        $this->runToolViaMakefile('test-phpstan-teamcity');
    }

    /**
     * @depends testPhpStan
     */
    public function testPsalm(): void
    {
        $this->runToolViaMakefile('test-psalm-teamcity');
    }

    /**
     * @depends testPsalm
     */
    public function testPhan(): void
    {
        $this->runToolViaMakefile('test-phan-teamcity');
    }

    /**
     * Test works only in PhpStorm or TeamCity if variable PHPSTORM_PROXY=1
     * Please, use `make codestyle` for any other environments.
     */
    protected function runToolViaMakefile(string $makeTargetName): void
    {
        $testCaseEnvVariable = $this->getEnvVariableForTestCase($makeTargetName);

        if (
            isPhpStorm()
            && (Env::bool('PHPSTORM_PROXY') || Env::bool($testCaseEnvVariable))
        ) {
            $phpBin = Env::string('PHP_BIN', 'php');

            $cliCommand = \implode(' ', [
                'TC_REPORT="tc-tests"',
                'TC_REPORT_MND="tc-tests"',
                'TEAMCITY_VERSION="2020.1.2"',
                "PHP_BIN=\"{$phpBin}\"",
                "make {$makeTargetName}",
            ]);

            // Redirect error to std output
            try {
                Cli::out(\trim(Cli::exec($cliCommand, [], PROJECT_ROOT)));
            } catch (\Exception $exception) {
                Cli::out(\trim($exception->getMessage()));
            }
        } else {
            skip("Define env.PHPSTORM_PROXY=1 or env.{$testCaseEnvVariable}=1");
        }

        success();
    }

    /**
     * @suppress PhanPluginPossiblyStaticPrivateMethod
     */
    private function getEnvVariableForTestCase(string $makeTargetName): string
    {
        $makeTargetName = \str_replace(['test-', '-teamcity', '-'], ['', '', '_'], $makeTargetName);
        $makeTargetName = \strtoupper($makeTargetName);

        return "PHPSTORM_PROXY_{$makeTargetName}";
    }
}
