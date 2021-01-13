<?php

/**
 * JBZoo Toolbox - Codestyle
 *
 * This file is part of the JBZoo Toolbox project.
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 *
 * @package    Codestyle
 * @license    MIT
 * @copyright  Copyright (C) JBZoo.com, All rights reserved.
 * @link       https://github.com/JBZoo/Codestyle
 */

if (\extension_loaded('xdebug')) {
    if (!function_exists('xdebug_enable')) {
        /**
         * Enable showing stack traces on error conditions.
         * @return void
         */
        function xdebug_enable(): void
        {
        }
    }

    if (!function_exists('xdebug_disable')) {
        /**
         * Disable showing stack traces on error conditions.
         * @return void
         */
        function xdebug_disable(): void
        {
        }
    }
}
