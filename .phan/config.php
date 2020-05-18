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

declare(strict_types=1);

// Example for depended projects
// $default = include __DIR__ . '/../vendor/jbzoo/codestyle/src/phan/default.php';

$default = include __DIR__ . '/../src/phan/default.php';

return array_merge($default, [
    'directory_list' => [
        // project
        'src',

        // 3rd party libs
        'vendor/phan/phan/src'
    ]
]);
