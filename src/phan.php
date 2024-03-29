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

// See for details ./vendor/phan/phan/src/Phan/Config.php
return [
    'target_php_version'         => null,
    'quick_mode'                 => false,
    'enable_include_path_checks' => false,
    'processes'                  => 1, // Cannot run analysis phase twice when using --processes N :(

    'allow_missing_properties' => false,
    'null_casts_as_any_type'   => false,
    'null_casts_as_array'      => false,
    'array_casts_as_null'      => false,

    'scalar_implicit_cast'    => false,
    'scalar_array_key_cast'   => false,
    'scalar_implicit_partial' => [],

    'strict_method_checking'   => true,
    'strict_object_checking'   => true,
    'strict_param_checking'    => true,
    'strict_property_checking' => true,
    'strict_return_checking'   => true,

    'ignore_undeclared_variables_in_global_scope'       => false,
    'ignore_undeclared_functions_with_known_signatures' => false,

    'backward_compatibility_checks'              => true,
    'check_docblock_signature_return_type_match' => true,
    'phpdoc_type_mapping'                        => [],

    'dead_code_detection'                      => false,
    'unused_variable_detection'                => true,
    'redundant_condition_detection'            => true,
    'assume_real_types_for_internal_functions' => true,

    'globals_type_map'     => [],
    'suppress_issue_types' => [
        'PhanCompatibleTrailingCommaParameterList',
    ],

    'minimum_severity' => 0, // Issue::SEVERITY_LOW,

    'analyzed_file_extensions'               => ['php'],
    'autoload_internal_extension_signatures' => [],

    'plugins' => [
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

        // Custom Rules
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
        // 'PHPDocRedundantPlugin'
    ],

    'file_list'      => [],
    'directory_list' => [],

    'exclude_file_regex'              => '@^vendor/.*/(tests?|Tests?)/@',
    'exclude_file_list'               => [],
    'exclude_analysis_directory_list' => [
        'vendor/',
        'tests/',
    ],
];
