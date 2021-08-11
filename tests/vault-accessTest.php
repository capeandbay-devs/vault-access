<?php

namespace capeandbay-devs\vault-access\Tests;

use capeandbay-devs\vault-access\Facades\vault-access;
use capeandbay-devs\vault-access\ServiceProvider;
use Orchestra\Testbench\TestCase;

class vault-accessTest extends TestCase
{
    protected function getPackageProviders($app)
    {
        return [ServiceProvider::class];
    }

    protected function getPackageAliases($app)
    {
        return [
            'vault-access' => vault-access::class,
        ];
    }

    public function testExample()
    {
        $this->assertEquals(1, 1);
    }
}
