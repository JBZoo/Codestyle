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

namespace JBZoo\CodeStyle\PHPUnit;

use JBZoo\PHPUnit\PHPUnit;
use JBZoo\Utils\Cli;
use JBZoo\Utils\Env;
use Symfony\Component\Finder\Finder;

use function JBZoo\PHPUnit\fail;
use function JBZoo\PHPUnit\isPhpStorm;
use function JBZoo\PHPUnit\isTrue;
use function JBZoo\PHPUnit\openFile;
use function JBZoo\PHPUnit\success;

abstract class AbstractCodeStyleTest extends PHPUnit
{
    // Configurations. Override it if you need for your project. #######################################################

    protected string $projectRoot = PROJECT_ROOT;

    /** @var string[] */
    protected array $excludePaths = [
        '.git',
        '.idea',
        'bin',
        'bower_components',
        'build',
        'fonts',
        'fixtures',
        'logs',
        'node_modules',
        'resources',
        'vendor',
        'temp',
        'tmp',
    ];

    /**
     * @throws \Exception
     */
    protected function setUp(): void
    {
        parent::setUp();

        if ($this->projectRoot === '') {
            throw new Exception('$this->projectRoot is undefined!');
        }

        if (!\class_exists(Finder::class)) {
            throw new Exception('symfony/finder is required for CodeStyle unit tests');
        }
    }

    public function testPhpCsFixer(): void
    {
        $this->runToolViaMakefile('test-phpcsfixer-teamcity');
    }

    public function testCodeSniffer(): void
    {
        $this->runToolViaMakefile('test-phpcs-teamcity');
    }

    public function testMessDetector(): void
    {
        $this->runToolViaMakefile('test-phpmd-teamcity');
    }

    public function testMagicNumbers(): void
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

    public function testFiles(): void
    {
        $finder = (new Finder())
            ->files()
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->ignoreDotFiles(false)
            ->name('*.html')
            ->name('*.xml')
            ->name('*.js')
            ->name('*.jsx')
            ->name('*.css')
            ->name('*.less')
            ->name('*.php')
            ->name('*.phtml')
            ->name('*.ini')
            ->name('*.json')
            ->name('*.txt')
            ->name('*.md')
            ->name('*.yml')
            ->name('*.yaml')
            ->name('.*.yml')
            ->name('.*.yaml')
            ->notName('*.min.*')
            ->exclude('*.Makefile')
            ->exclude('Makefile');

        /** @var \SplFileInfo $file */
        foreach ($finder as $file) {
            $content = (string)openFile($file->getPathname());

            if ($content !== '') {
                isTrue(
                    !\str_contains($content, "\r"),
                    'The file contains prohibited symbol "\r" (CARRIAGE RETURN) : ' . $file->getPathname(),
                );

                isTrue(
                    !\str_contains($content, "\t"),
                    'The file contains prohibited symbol "\t" (TAB) : ' . $file->getPathname(),
                );
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    public function testCyrillic(): void
    {
        $finder = (new Finder())
            ->files()
            ->ignoreDotFiles(false)
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->notName([
                'AbstractCodeStyleTest\.php',
                '/\.md$/',
                '/\.min\.(js|css)$/',
                '/\.min\.(js|css)\.map$/',
            ]);

        foreach ($finder as $file) {
            $content = openFile($file->getPathname());

            if (\preg_match('#[А-Яа-яЁё]#iu', (string)$content) > 0) {
                fail('File contains cyrillic symbols: ' . $file); // Short message in terminal
            }
        }

        isTrue(true); // One assert is a minimum for test complete
    }

    // ### Test cases ##################################################################################################

    protected function runToolViaMakefile(string $makeTargetName): void
    {
        // Test works only in PhpStorm ot TeamCity env. Please, use `make codestyle` for any other environments.
        if (isPhpStorm()) {
            $phpBin     = Env::string('PHP_BIN', 'php');
            $cliCommand = \implode(' ', [
                'TC_REPORT="tc-tests"',
                'TC_REPORT_MND="tc-tests"',
                'TEAMCITY_VERSION="2020.1.2"',
                "PHP_BIN=\"{$phpBin}\"",
                "make {$makeTargetName}",
            ]);

            // redirect error to std output
            try {
                $output = \trim(Cli::exec($cliCommand, [], $this->projectRoot));
                Cli::out($output);
            } catch (\Exception $exception) {
                $output = \trim($exception->getMessage());
                Cli::out($output);
            }
        }

        success();
    }
}
