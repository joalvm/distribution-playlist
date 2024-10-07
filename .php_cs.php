<?php

$finder = (new PhpCsFixer\Finder())
    ->exclude('vendor')
    ->in(getcwd())
    ->ignoreDotFiles(true)
    ->ignoreVCS(true)
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PSR12' => true,
        '@Symfony' => true,
        'concat_space' => ['spacing' => 'one'],
        'php_unit_method_casing' => ['case' => 'snake_case'],
        'explicit_string_variable' => true,
        'new_with_braces' => true,
        'no_unused_imports' => true,
        'ternary_to_null_coalescing' => true,
        'single_line_throw' => false,
        'assign_null_coalescing_to_coalesce_equal' => true,
        'multiline_whitespace_before_semicolons' => [
            'strategy' => 'new_line_for_chained_calls',
        ],
        'ordered_imports' => [
            'sort_algorithm' => 'alpha',
        ],
        'no_extra_blank_lines' => [
            'tokens' => [
                'curly_brace_block',
                'square_brace_block',
                'parenthesis_brace_block',
                'continue',
                'extra',
                'throw',
                'use',
                'use_trait',
                'return',
            ],
        ],
        'phpdoc_no_alias_tag' => false,
        'phpdoc_param_order' => true,
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_add_missing_param_annotation' => true,
        'phpdoc_separation' => true,
        'phpdoc_align' => [
            'align' => 'vertical',
            'tags' => [
                'method',
                'param',
                'property',
                'property-read',
                'property-write',
                'return',
                'throws',
                'type',
                'var',
            ],
        ],
    ])
    ->setFinder($finder)
    ->setUsingCache(false)
;
