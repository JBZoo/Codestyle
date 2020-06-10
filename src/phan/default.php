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

use Phan\Issue;

// See for details ./vendor/phan/phan/src/Phan/Config.php
// @codeCoverageIgnore

return [
    'target_php_version'         => null,
    'quick_mode'                 => false,
    'enable_include_path_checks' => false,
    'processes'                  => 1,

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
    'suppress_issue_types' => [],
    'minimum_severity'     => Issue::SEVERITY_LOW,

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
    ],

    'file_list'      => [],
    'directory_list' => [],

    'exclude_file_regex'              => '@^vendor/.*/(tests?|Tests?)/@',
    'exclude_file_list'               => [],
    'exclude_analysis_directory_list' => [
        'vendor/',
    ],
];
