<?php

return [
    /*
    |--------------------------------------------------------------------------
    | NIE Validation Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration options for Spanish NIE (Número de Identidad de Extranjero)
    | validation. These settings control the behavior of the NIE validator.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | Control Letters
    |--------------------------------------------------------------------------
    |
    | The official sequence of control letters used for NIE validation.
    | This sequence is defined by Spanish regulation (Real Decreto 1553/2005).
    | Index corresponds to the remainder when dividing the numeric part by 23.
    |
    | DO NOT MODIFY unless the official regulation changes.
    |
    */
    'control_letters' => str_split('TRWAGMYFPDXBNJZSQVHLCKE'),

    /*
    |--------------------------------------------------------------------------
    | Validation Strictness
    |--------------------------------------------------------------------------
    |
    | Controls whether to allow common formatting variations:
    | - true: Only accept exact format (X1234567L)
    | - false: Allow spaces and hyphens (X-1234567-L, X 1234567 L)
    |
    | Default: false (lenient, allows common formatting)
    |
    */
    'strict_format' => false,
];
