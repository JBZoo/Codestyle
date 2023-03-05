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

use Symfony\Component\VarDumper\Cloner\VarCloner;
use Symfony\Component\VarDumper\Dumper\AbstractDumper;
use Symfony\Component\VarDumper\Dumper\CliDumper;
use Symfony\Component\VarDumper\VarDumper;

$autoload = \dirname(__DIR__) . '/vendor/autoload.php';
if (\file_exists($autoload)) {
    require_once $autoload;
} else {
    echo 'Please execute "composer update" !' . \PHP_EOL;
    exit(1);
}

VarDumper::setHandler(static function (mixed $variable): void {
    $maxStringWidth = 16 * 1024; // Show first 16kb only, optimization

    $varCloner = new VarCloner();
    $varCloner->setMaxItems(500);
    $varCloner->setMaxString($maxStringWidth);

    $cliDumper = new CliDumper(null, 'UTF-8', AbstractDumper::DUMP_COMMA_SEPARATOR);
    $cliDumper->setMaxStringWidth($maxStringWidth);
    $cliDumper->setIndentPad('    '); // Classic style
    $cliDumper->setDisplayOptions(['fileLinkFormat' => false]); // Disable file links for PHPStorm

    $varClone = $varCloner->cloneVar($variable);

    $cliDumper->dump($varClone);
});
