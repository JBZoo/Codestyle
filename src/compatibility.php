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

declare(strict_types=1);

// @codeCoverageIgnore

if (\extension_loaded('xdebug')) {
    if (!function_exists('xdebug_enable')) {
        /**
         * Enable showing stack traces on error conditions.
         * @phan-suppress PhanRedefineFunctionInternal
         * @return void
         */
        function xdebug_enable(): void
        {
        }
    }

    if (!function_exists('xdebug_disable')) {
        /**
         * Disable showing stack traces on error conditions.
         * @phan-suppress PhanRedefineFunctionInternal
         * @return void
         */
        function xdebug_disable(): void
        {
        }
    }

    if (!function_exists('xdebug_get_declared_vars')) {
        /**
         * Returns an array where each element is a variable name which is defined in the current scope.
         * @phan-suppress PhanRedefineFunctionInternal
         * @return array
         */
        function xdebug_get_declared_vars(): array
        {
            return [];
        }
    }

    if (!function_exists('xdebug_get_declared_vars')) {
        /**
         * Return whether stack traces would be shown in case of an error or not.
         * @phan-suppress PhanRedefineFunctionInternal
         * @return bool
         */
        function xdebug_is_enabled(): bool
        {
            return true;
        }
    }
}
