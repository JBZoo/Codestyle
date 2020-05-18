<?php

/**
 * JBZoo Toolbox - PHPUnit
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    PHPUnit
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/PHPUnit
 */

if (file_exists(__DIR__ . '/vendor/jbzoo/codestyle/.phan/default.php')) {
    $default = include __DIR__ . '/vendor/jbzoo/codestyle/.phan/default.php';
} else {
    $default = include __DIR__ . '/default.php';
}

return array_merge($default, [
    'directory_list' => [
        getenv('PATH_SRC') ?: 'src'
    ]
]);
