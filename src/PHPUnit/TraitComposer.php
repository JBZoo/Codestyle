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

use function JBZoo\Data\json;
use function JBZoo\PHPUnit\isContain;
use function JBZoo\PHPUnit\isNotEmpty;
use function JBZoo\PHPUnit\isSame;
use function JBZoo\PHPUnit\skip;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitComposer
{
    public function testComposerBranchAlias(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame($this->composerDevVersion, $composer->find('extra.branch-alias.dev-master'));
    }

    public function testComposerRequirePhp(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame($this->composerPhpVersion, $composer->find('require.php'));
    }

    public function testComposerLicense(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame($this->composerLicense, $composer->find('license'));
    }

    public function testComposerType(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame($this->composerType, $composer->find('type'));
    }

    public function testComposerPackageName(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isContain(\strtolower("{$this->vendorName}/{$this->packageName}"), $composer->find('name'));
    }

    public function testComposerAutoloadDev(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame(["{$this->vendorName}\\PHPUnit\\" => 'tests'], $composer->find('autoload-dev.psr-4'));
    }

    public function testComposerPreferStable(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame(true, $composer->find('prefer-stable'));
    }

    public function testComposer(): void
    {
        skip('TODO: Complete tests from comments');

        // jbzoo/* => !dev-master
        // test phpunit.xml
        // test makefile, update
        // check public docs and length
        // final classes for tests
        // @return $this (and + ./tests)
        // @throws (and + ./tests)
        // build-download-phars - add all deps to download at once
        // composer update -W everywhere
        // composer order of properties
        // check banner in `box.json.dist`
        // Move tests from PhpStormProxy => AbstractPackageTest
        // test .gitattributes
    }

    public function testComposerAutoload(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame(["{$this->vendorName}\\{$this->packageNamespace}\\" => 'src'], $composer->find('autoload.psr-4'));
    }

    public static function testComposerKeywords(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isNotEmpty($composer->find('keywords'));
    }

    public static function testComposerDescription(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isNotEmpty($composer->find('description'));
    }

    public static function testComposerOptimizeAutoloader(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame(true, $composer->find('config.optimize-autoloader'));
    }

    public static function testComposerAllowPlugins(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame(true, $composer->find('config.allow-plugins.composer/package-versions-deprecated'));
    }

    public static function testComposerMinimumStability(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');
        isSame('dev', $composer->find('minimum-stability'));
    }

    public static function testComposerAuthor(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');

        isSame([
            'name'  => 'Denis Smetannikov',
            'email' => 'admin@jbzoo.com',
            'role'  => 'lead',
        ], $composer->find('authors.0'));
    }
}
