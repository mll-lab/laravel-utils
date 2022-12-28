<?php declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | State Model Class
    |--------------------------------------------------------------------------
    |
    | Fully qualified class name of the model class used to store the state.
    |
    */
    'model' => App\ModelStates\StateManager::class,

    /*
    |--------------------------------------------------------------------------
    | State Column Name
    |--------------------------------------------------------------------------
    |
    | This value determines the column name in which the actual state will be saved.
    |
    */
    'column_name' => 'state_name',
];
