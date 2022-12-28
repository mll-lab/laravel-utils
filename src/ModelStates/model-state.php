<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | State Model Class
    |--------------------------------------------------------------------------
    |
    | This value determines which model ist the actually used State-Model.
    | Replace with root application state class.
    |
    */
    'model' => MLL\LaravelUtils\Tests\ModelStates\Dummy\StateManager::class,

    /*
    |--------------------------------------------------------------------------
    | State Column Name
    |--------------------------------------------------------------------------
    |
    | This value determines the column name in which the actual state will be saved.
    |
    */
    'column_name' => 'state_name',

    /*
    |--------------------------------------------------------------------------
    | State Relation Name
    |--------------------------------------------------------------------------
    |
    | This value the name of the morphed state relation.
    |
    */
    'relation_name' => 'stateable',
];
