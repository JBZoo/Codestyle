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
use function JBZoo\PHPUnit\isContain;
use function JBZoo\PHPUnit\isNotEmpty;
use function JBZoo\PHPUnit\isSame;

abstract class AbstractComposerTest extends PHPUnit
{
    protected string $authorName  = 'Denis Smetannikov';
    protected string $authorEmail = 'admin@jbzoo.com';
    protected string $authorRole  = 'lead';
    protected string $devVersion  = '7.x-dev';
    protected string $phpVersion  = '^8.1';

    public function test(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');

        isSame($this->authorName, $composer->find('authors.0.name'));
        isSame($this->authorEmail, $composer->find('authors.0.email'));
        isSame($this->authorRole, $composer->find('authors.0.role'));
        isSame($this->devVersion, $composer->find('extra.branch-alias.dev-master'));
        isSame($this->phpVersion, $composer->find('require.php'));
        isSame('MIT', $composer->find('license'));

        isContain('jbzoo/', $composer->find('name'));
        isNotEmpty($composer->find('type'));
        isNotEmpty($composer->find('keywords'));
        isNotEmpty($composer->find('description'));

        isSame(['JBZoo\PHPUnit\\' => 'tests'], $composer->find('autoload-dev.psr-4'));
        isSame(true, $composer->find('config.optimize-autoloader'));
        isSame(true, $composer->find('config.allow-plugins.composer/package-versions-deprecated'));
    }
}
