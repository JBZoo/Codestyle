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

/**
 * @BeforeMethods({"init"})
 * @Revs(10000)
 * @Iterations(5)
 */
class PhpHashFunctions
{
    private string $string;

    public function init(): void
    {
        $this->string = \str_repeat((string)\random_int(0, 9), 1024);
    }

    /**
     * @Groups({"md5", "Native"})
     */
    public function benchMd5Native(): void
    {
        \md5($this->string);
    }

    /**
     * @Groups({"md5", "hash()"})
     */
    public function benchMd5(): void
    {
        \hash('md5', $this->string);
    }

    /**
     * @Groups({"sha1", "Native"})
     */
    public function benchSha1Native(): void
    {
        \sha1($this->string);
    }

    /**
     * @Groups({"sha1", "hash()"})
     */
    public function benchSha1(): void
    {
        \hash('sha1', $this->string);
    }

    /**
     * @Groups({"crc32", "Native"})
     */
    public function benchCrc32Native(): void
    {
        \crc32($this->string);
    }

    /**
     * @Groups({"crc32", "hash()"})
     */
    public function benchCrc32(): void
    {
        \hash('crc32', $this->string);
    }
}
