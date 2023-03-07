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

namespace JBZoo\Codestyle\PhpCsFixer;

use PhpCsFixer\Config;
use PhpCsFixer\ConfigInterface;
use PhpCsFixerCustomFixers\Fixer\NoDuplicatedArrayKeyFixer;
use PhpCsFixerCustomFixers\Fixer\NoUselessCommentFixer;
use PhpCsFixerCustomFixers\Fixers;
use Symfony\Component\Finder\Finder;

/**
 * See details: https://mlocati.github.io/php-cs-fixer-configurator.
 */
final class PhpCsFixerCodingStandard
{
    private array $ruleSet = [
        // Presets
        '@PHP80Migration'           => true,
        '@PHP80Migration:risky'     => true,
        '@PHPUnit84Migration:risky' => true,
        '@Symfony'                  => true,
        '@Symfony:risky'            => true,
        '@PhpCsFixer'               => true,
        '@PhpCsFixer:risky'         => true,
        '@PSR12'                    => true,
        '@PSR12:risky'              => true,

        // The most dangerous options! They are excluded from above presets. Just in case.
        'no_trailing_whitespace_in_string'      => false,
        'no_unneeded_final_method'              => false,
        'strict_comparison'                     => false, // TODO: Check ho many issues we have in real code.
        'error_suppression'                     => false, // TODO: Check ho many issues we have in real code.
        'no_unreachable_default_argument_value' => false, // TODO: Check ho many issues we have in real code.
        'no_unset_on_property'                  => false, // TODO: Check ho many issues we have in real code.

        // PHPDocs & Comments
        'align_multiline_comment'    => ['comment_type' => 'phpdocs_like'],
        'phpdoc_separation'          => false,
        'phpdoc_to_comment'          => false,
        'no_superfluous_phpdoc_tags' => [
            'allow_mixed'         => true,
            'allow_unused_params' => true,
        ],
        'phpdoc_line_span' => [
            'const'    => 'single',
            'property' => 'single',
            'method'   => 'multi',
        ],

        // PHPUnit
        'php_unit_internal_class'                => false,
        'php_unit_strict'                        => false,
        'php_unit_test_case_static_method_calls' => ['call_type' => 'self'],
        'php_unit_test_class_requires_covers'    => false,

        // Import & ordering rules
        'no_unused_imports'                => true,
        'blank_line_between_import_groups' => true,
        'fully_qualified_strict_types'     => true,
        'global_namespace_import'          => [
            'import_classes'   => false,
            'import_constants' => false,
            'import_functions' => false,
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
            'imports_order'  => ['class', 'function', 'const'],
        ],
        'ordered_interfaces'     => ['direction' => 'ascend', 'order' => 'alpha'],
        'ordered_class_elements' => [
            'order' => [
                'use_trait',
                'case',
                // Const
                'constant',
                'constant_public',
                'constant_protected',
                'constant_private',
                // Props
                'property',
                'property_public_static',
                'property_protected_static',
                'property_private_static',
                'property_public',
                'property_protected',
                'property_private',
                // Statics
                'method_public_abstract_static',
                'method_protected_abstract_static',
                'method_public_abstract',
                'method_protected_abstract',
                // Magic methods
                'construct',
                'destruct',
                'magic',
                'phpunit',
                // Regular Methods
                'method',
                'method_public_static',
                'method_protected_static',
                'method_private_static',
                'method_public',
                'method_protected',
                'method_private',
            ],
        ],

        'binary_operator_spaces' => [
            'operators' => [
                '='  => 'align_single_space_minimal',
                '=>' => 'align_single_space_minimal',
            ],
        ],

        // Blank lines & spaces
        'control_structure_braces'    => true,
        'method_chaining_indentation' => true,

        'cast_spaces'  => ['space' => 'none'],
        'types_spaces' => ['space' => 'none'],
        'concat_space' => ['spacing' => 'one'],

        'multiline_whitespace_before_semicolons' => ['strategy' => 'no_multi_line'],
        'trailing_comma_in_multiline'            => [
            'after_heredoc' => true,
            'elements'      => ['arrays', 'arguments', 'parameters'],
        ],

        'blank_line_before_statement' => [
            'statements' => [
                'case',
                'default',
                'declare',
                'do',
                'for',
                'foreach',
                'return',
                'switch',
                'try',
                'while',
                'phpdoc',
            ],
        ],

        'no_extra_blank_lines' => [
            'tokens' => [
                'attribute',
                'break',
                'case',
                'continue',
                'curly_brace_block',
                'default',
                'extra',
                'parenthesis_brace_block',
                'return',
                'square_brace_block',
                'switch',
                'throw',
                'use',
            ],
        ],

        'braces' => [
            'allow_single_line_closure'                         => true,
            'allow_single_line_anonymous_class_with_empty_body' => true,
        ],

        // Extra rules
        'increment_style' => ['style' => 'post'],
        'yoda_style'      => [
            'equal'            => true,
            'identical'        => false,
            'less_and_greater' => false,
        ],
        'regular_callable_call'      => true,
        'self_static_accessor'       => true,
        'simplified_if_return'       => true,
        'static_lambda'              => true,
        'native_function_invocation' => [
            'scope'   => 'all',
            'strict'  => true,
            'include' => ['@internal'],
        ],
        'native_constant_invocation' => [
            'fix_built_in' => true,
            'include'      => [
                'DIRECTORY_SEPARATOR',
                'PHP_INT_SIZE',
                'PHP_SAPI',
                'PHP_VERSION_ID',
            ],
            'scope'  => 'all',
            'strict' => true,
        ],

        'control_structure_continuation_position'          => true,
        'nullable_type_declaration_for_default_null_value' => true,

        // For the future
        // 'final_class' => true
    ];

    private string $projectPath;
    private string $styleName = 'JBZooStyle';

    public function __construct(string $projectPath)
    {
        $path = \realpath($projectPath);

        if ($path === false) {
            throw new \RuntimeException("Project path \"{$projectPath}\" is not valid");
        }

        $this->projectPath = $path;
    }

    public function getRules(): array
    {
        return $this->ruleSet;
    }

    public function getFixerConfig(?Finder $customFinder = null, array $customRules = []): ConfigInterface
    {
        $customRules = [
            NoDuplicatedArrayKeyFixer::name() => true,
            NoUselessCommentFixer::name()     => true,
        ] + $customRules;

        return (new Config($this->styleName))
            ->setRiskyAllowed(true)
            ->registerCustomFixers(new Fixers())
            ->setCacheFile("{$this->projectPath}/build/php-cs-fixer-cache.json")
            ->setFinder($customFinder ?? $this->getFinder())
            ->setRules($this->getRules() + $customRules);
    }

    public function getFinder(): Finder
    {
        return (new Finder())
            ->files()
            ->followLinks()
            ->ignoreVCS(true)
            ->ignoreDotFiles(false)
            ->in($this->projectPath)
            ->exclude('vendor')
            ->exclude('build')
            ->name('/\.php$/');
    }
}
