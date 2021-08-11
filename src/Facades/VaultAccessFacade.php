<?php

namespace CapeAndBay\VaultAccess\Facades;

use Illuminate\Support\Facades\Facade;

class VaultAccessFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'vault-access';
    }
}
