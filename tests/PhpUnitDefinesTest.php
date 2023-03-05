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

namespace JBZoo\PHPUnit;

use JBZoo\CodeStyle\PhpCsFixer\PhpCsFixerCodingStandard;

/**
 * @internal
 * @coversNothing
 */
final class PhpUnitDefinesTest extends PHPUnit
{
    public function testDefines(): void
    {
        isSame(\realpath(__DIR__ . '/..'), PROJECT_ROOT);
        isSame(\realpath(__DIR__ . '/../src'), PROJECT_SRC);
        isSame(\realpath(__DIR__ . '/../tests'), PROJECT_TESTS);
        isSame(\realpath(__DIR__ . '/../build'), PROJECT_BUILD);
        isSame(true, JBZOO_PHPUNIT);
        isSame('/', \DIRECTORY_SEPARATOR);
        isSame("\r\n", CRLF);
        isSame("\n", LF);
        isSame("\n", \PHP_EOL);
    }

    public function testPhanConfig(): void
    {
        $configs = include PROJECT_ROOT . '/.phan.php';
        isSame([
            'target_php_version'                                => null,
            'quick_mode'                                        => false,
            'enable_include_path_checks'                        => false,
            'processes'                                         => 1,
            'allow_missing_properties'                          => false,
            'null_casts_as_any_type'                            => false,
            'null_casts_as_array'                               => false,
            'array_casts_as_null'                               => false,
            'scalar_implicit_cast'                              => false,
            'scalar_array_key_cast'                             => false,
            'scalar_implicit_partial'                           => [],
            'strict_method_checking'                            => true,
            'strict_object_checking'                            => true,
            'strict_param_checking'                             => true,
            'strict_property_checking'                          => true,
            'strict_return_checking'                            => true,
            'ignore_undeclared_variables_in_global_scope'       => false,
            'ignore_undeclared_functions_with_known_signatures' => false,
            'backward_compatibility_checks'                     => true,
            'check_docblock_signature_return_type_match'        => true,
            'phpdoc_type_mapping'                               => [],
            'dead_code_detection'                               => false,
            'unused_variable_detection'                         => true,
            'redundant_condition_detection'                     => true,
            'assume_real_types_for_internal_functions'          => true,
            'globals_type_map'                                  => [],
            'suppress_issue_types'                              => [],
            'minimum_severity'                                  => 0,
            'analyzed_file_extensions'                          => ['php'],
            'autoload_internal_extension_signatures'            => [],
            'plugins'                                           => [
                'AlwaysReturnPlugin',
                'DollarDollarPlugin',
                'DuplicateArrayKeyPlugin',
                'DuplicateExpressionPlugin',
                'PregRegexCheckerPlugin',
                'PrintfCheckerPlugin',
                'SleepCheckerPlugin',
                'UnreachableCodePlugin',
                'UseReturnValuePlugin',
                'EmptyStatementListPlugin',
                'StrictComparisonPlugin',
                'LoopVariableReusePlugin',
                'DuplicateConstantPlugin',
                'FFIAnalysisPlugin',
                'InlineHTMLPlugin',
                'InvalidVariableIssetPlugin',
                'InvokePHPNativeSyntaxCheckPlugin',
                'PhanSelfCheckPlugin',
                'PreferNamespaceUsePlugin',
                'ShortArrayPlugin',
                'StrictLiteralComparisonPlugin',
                'SuspiciousParamOrderPlugin',
                'WhitespacePlugin',
                'SimplifyExpressionPlugin',
                'RemoveDebugStatementPlugin',
                'RedundantAssignmentPlugin',
                'PossiblyStaticMethodPlugin',
                'PHPDocToRealTypesPlugin',
                'UnusedSuppressionPlugin',
            ],
            'file_list'      => [],
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
            'exclude_file_regex' => '@^vendor/.*/(tests?|Tests?)/@',
            'exclude_file_list'  => [
                'src/compatibility.php',
            ],
            'exclude_analysis_directory_list' => [
                'vendor/',
                'tests/',
            ],
        ], $configs);
    }

    public function testPhpCsFixerConfig(): void
    {
        $config = (new PhpCsFixerCodingStandard(__DIR__ . '/..'))
            ->getFixerConfig();

        isTrue($config->getUsingCache());
        isContain('/build/php-cs-fixer-cache.json', $config->getCacheFile());
        isSame('    ', $config->getIndent());
        isSame("\n", $config->getLineEnding());
        isSame('JBZoo Style', $config->getName());
    }

    public function testPhpCsFixerConfigAsFile(): void
    {
        $config = include \realpath(__DIR__ . '/../src/PhpCsFixer/php-cs-fixer.php');

        isTrue($config->getUsingCache());
        isContain('/build/php-cs-fixer-cache.json', $config->getCacheFile());
        isSame('    ', $config->getIndent());
        isSame("\n", $config->getLineEnding());
        isSame('JBZoo Style', $config->getName());
    }
}
