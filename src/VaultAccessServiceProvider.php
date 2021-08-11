<?php

namespace CapeAndBay\VaultAccess;

use Illuminate\Support\ServiceProvider;

class VaultAccessServiceProvider extends ServiceProvider
{
    const CONFIG_PATH = __DIR__ . '/../config/vault-access.php';

    public function boot()
    {
        $this->loadConfigs();

        $this->publishFiles();

        $this->loadRoutes();

        $this->publishes([
            self::CONFIG_PATH => config_path('vault-access.php'),
        ], 'config');
    }

    public function register()
    {
        $this->mergeConfigFrom(
            self::CONFIG_PATH,
            'vault-access'
        );

        $this->app->bind('vault-access', function () {
            return new VaultAccess();
        });
    }

    public function loadConfigs()
    {
        // use the vendor configuration file as fallback
        $this->mergeConfigFrom(__DIR__.'/../config/vault-access.php', 'nautical');
    }

    public function publishFiles()
    {
        $capeandbay_config_files = [__DIR__.'/config' => config_path()];

        $minimum = array_merge(
            $capeandbay_config_files
        );

        // register all possible publish commands and assign tags to each
        $this->publishes($capeandbay_config_files, 'config');
        $this->publishes($minimum, 'minimum');
    }

    public function loadRoutes()
    {
        $this->loadRoutesFrom(__DIR__.'/routes/capeandbay/vault-access.php');
    }
}
