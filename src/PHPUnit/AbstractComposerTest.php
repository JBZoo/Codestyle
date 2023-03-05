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

use function JBZoo\Data\json;
use function JBZoo\PHPUnit\isNotEmpty;
use function JBZoo\PHPUnit\isSame;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
abstract class AbstractComposerTest extends PHPUnit
{
    protected string $authorName  = 'Denis Smetannikov';
    protected string $authorEmail = 'admin@jbzoo.com';
    protected string $authorRole  = 'lead';
    protected string $devBranch   = 'dev-master';
    protected string $phpVersion  = '^8.1';

    public function testAuthor(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        if ($this->authorName !== '') {
            isSame($this->authorName, $composerJson->find('authors.0.name'), "See file: {$composerPath}");
        }

        if ($this->authorEmail !== '') {
            isSame($this->authorEmail, $composerJson->find('authors.0.email'), "See file: {$composerPath}");
        }

        if ($this->authorRole !== '') {
            isSame($this->authorRole, $composerJson->find('authors.0.role'), "See file: {$composerPath}");
        }
    }

    public function testDevMasterAlias(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        isNotEmpty($composerJson->find("extra.branch-alias.{$this->devBranch}"), "See file: {$composerPath}");
    }

    public function testPhpRequirements(): void
    {
        $composerPath = PROJECT_ROOT . '/composer.json';
        $composerJson = json($composerPath);

        isSame($this->phpVersion, $composerJson->find('require.php'), "See file: {$composerPath}");
    }
}
