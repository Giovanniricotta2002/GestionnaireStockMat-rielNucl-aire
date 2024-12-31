<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'yoda_style' => false,
        'phpdoc_to_comment' => true,
        'explicit_string_variable' => true,
        'concat_space' => ['spacing' => 'one'],
        'increment_style' => ['style' => 'post'],
    ])
    ->setFinder($finder)
;
