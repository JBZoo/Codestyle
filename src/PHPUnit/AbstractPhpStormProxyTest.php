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
use function JBZoo\PHPUnit\success;

abstract class AbstractPhpStormProxyTest extends PHPUnit
{
    public function testPhpCsFixer(): void
    {
        $this->runToolViaMakefile('test-phpcsfixer-teamcity');
    }

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

    public function testPsalm(): void
    {
        $this->runToolViaMakefile('test-psalm-teamcity');
    }

    public function testPhan(): void
    {
        $this->runToolViaMakefile('test-phan-teamcity');
    }

    protected function runToolViaMakefile(string $makeTargetName): void
    {
        // Test works only in PhpStorm ot TeamCity env. Please, use `make codestyle` for any other environments.
        if (Env::bool('PHPSTORM_PROXY', false) && isPhpStorm()) {
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
                $output = \trim(Cli::exec($cliCommand, [], PROJECT_ROOT));
                Cli::out($output);
            } catch (\Exception $exception) {
                $output = \trim($exception->getMessage());
                Cli::out($output);
            }
        }
        success();
    }
}
