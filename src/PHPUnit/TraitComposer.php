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

use function JBZoo\Data\json;
use function JBZoo\PHPUnit\isContain;
use function JBZoo\PHPUnit\isNotEmpty;
use function JBZoo\PHPUnit\isSame;

/**
 * @phan-file-suppress PhanUndeclaredProperty
 */
trait TraitComposer
{
    public function testComposerJsonProperties(): void
    {
        $composer = json(PROJECT_ROOT . '/composer.json');

        isSame([
            'name'  => 'Denis Smetannikov',
            'email' => 'admin@jbzoo.com',
            'role'  => 'lead',
        ], $composer->find('authors.0'));

        isSame($this->composerDevVersion, $composer->find('extra.branch-alias.dev-master'));
        isSame($this->composerPhpVersion, $composer->find('require.php'));
        isSame('MIT', $composer->find('license'));

        isContain(\strtolower("{$this->vendorName}/{$this->packageName}"), $composer->find('name'));
        isNotEmpty($composer->find('type'));
        isNotEmpty($composer->find('keywords'));
        isNotEmpty($composer->find('description'));

        isSame(['JBZoo\PHPUnit\\' => 'tests'], $composer->find('autoload-dev.psr-4'));
        isSame(true, $composer->find('config.optimize-autoloader'));
        isSame(true, $composer->find('config.allow-plugins.composer/package-versions-deprecated'));
    }
}
