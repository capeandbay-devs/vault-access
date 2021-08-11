<?php

Route::group(
    [
        'prefix' => 'vault-api',
        'namespace'  => 'CapeAndBay\VaultAccess\Actions',
        'middleware' => 'auth:api'
    ],
    function() {

        Route::post('/vaults', 'Vaults\ListVaults');
        Route::post('/vaults/items', 'Vaults\ListItems');
        Route::post('/vaults/items/details', 'Vaults\ItemDetails');
    }
);
