<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('var')
;

return PhpCsFixer\Config::create()
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        '@Symfony:risky' => true,
        // php file
        'concat_space' => ['spacing' => 'one'],
        // namespace and imports
        'ordered_imports' => true,
        // standard functions and operators
        'native_constant_invocation' => false,
        'native_function_invocation' => false,
        'modernize_types_casting' => true,
        'is_null' => true,
        // arrays
        'array_syntax' => [
            'syntax' => 'short',
        ],
        // phpdoc
        'phpdoc_annotation_without_dot' => false,
        'phpdoc_summary' => false,
        'phpdoc_align' => false,
        'no_superfluous_phpdoc_tags' => false,
        //logical operators
        'logical_operators' => true,
        ])
    ->setFinder($finder)
;