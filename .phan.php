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

// Example for depended on projects
// $default = include __DIR__ . '/vendor/jbzoo/codestyle/src/phan/default.php';

$default = include __DIR__ . '/src/phan.php';

return \array_merge($default, [
    'directory_list' => [
        'src',

        'vendor/jbzoo/data',
        'vendor/jbzoo/utils',
        'vendor/jbzoo/phpunit',
        'vendor/jbzoo/markdown',

        'vendor/phpunit/phpunit/src',
        'vendor/symfony/finder',
        'vendor/friendsofphp/php-cs-fixer',
    ],

    'exclude_file_list' => [
        'src/compatibility.php',
    ],
]);
