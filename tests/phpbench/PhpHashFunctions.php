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

/**
 * Class PhpHashFunctions
 * @BeforeMethods({"init"})
 * @Revs(100000)
 * @Iterations(10)
 */
class PhpHashFunctions
{
    /**
     * @var string
     */
    private $string;

    public function init(): void
    {
        $this->string = str_repeat(random_int(0, 9), 1024);
    }


    /**
     * @Groups({"md5", "Native"})
     */
    public function benchMd5Native()
    {
        md5($this->string);
    }

    /**
     * @Groups({"md5", "hash()"})
     */
    public function benchMd5()
    {
        hash('md5', $this->string);
    }


    /**
     * @Groups({"sha1", "Native"})
     */
    public function benchSha1Native()
    {
        sha1($this->string);
    }

    /**
     * @Groups({"sha1", "hash()"})
     */
    public function benchSha1()
    {
        hash('sha1', $this->string);
    }


    /**
     * @Groups({"crc32", "Native"})
     */
    public function benchCrc32Native()
    {
        crc32($this->string);
    }

    /**
     * @Groups({"crc32", "hash()"})
     */
    public function benchCrc32()
    {
        hash('crc32', $this->string);
    }
}
