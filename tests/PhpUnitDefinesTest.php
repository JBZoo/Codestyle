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
 * @author     Denis Smetannikov <denis@jbzoo.com>
 */

namespace JBZoo\PHPUnit;

/**
 * Class PhphUnitDefinesTest
 * @package JBZoo\PHPUnit
 */
class PhpUnitDefinesTest extends PHPUnit
{
    public function testDefines()
    {
        isSame(realpath(__DIR__ . '/..'), PROJECT_ROOT);
        isSame(realpath(__DIR__ . '/../src'), PROJECT_SRC);
        isSame(realpath(__DIR__ . '/../tests'), PROJECT_TESTS);
        isSame(realpath(__DIR__ . '/../build'), PROJECT_BUILD);
        isSame(true, JBZOO_PHPUNIT);
        isSame('/', DIRECTORY_SEPARATOR);
        isSame("\r\n", CRLF);
        isSame("\n", LF);
        isSame("\n", PHP_EOL);
    }
}
