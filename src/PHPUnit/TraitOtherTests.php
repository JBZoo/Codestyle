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

use Symfony\Component\Finder\Finder;

use function JBZoo\PHPUnit\isTrue;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitOtherTests
{
    // Configurations. Override it if you need for your project. #######################################################

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

    public function testHiddenSpacesInCode(): void
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

        AbstractPackageTest::checkFiles(__METHOD__, $finder, static function (string $content, string $pathname): void {
            isTrue(
                !\str_contains($content, "\r"),
                'The file contains prohibited symbol "\\r" (CARRIAGE RETURN) : ' . $pathname,
            );

            isTrue(
                !\str_contains($content, "\t"),
                'The file contains prohibited symbol "\\t" (TAB) : ' . $pathname,
            );
        });
    }

    public function testCyrillic(): void
    {
        $finder = (new Finder())
            ->files()
            ->ignoreDotFiles(false)
            ->in($this->projectRoot)
            ->exclude($this->excludePaths)
            ->notName([
                \basename(__FILE__),
                '/\.md$/',
                '/\.min\.(js|css)$/',
                '/\.min\.(js|css)\.map$/',
            ]);

        AbstractPackageTest::checkFiles(__METHOD__, $finder, static function (string $content, string $pathname): void {
            isTrue(
                \preg_match('#[А-Яа-яЁё]#iu', $content) === 0,
                'The file contains cyrillic symbols: ' . $pathname,
            );
        });
    }
}
