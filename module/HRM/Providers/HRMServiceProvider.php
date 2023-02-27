<?php

namespace Module\HRM\Providers;

use Illuminate\Support\ServiceProvider;

class HRMServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        $this->loadMigrationsFrom([
            base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'HRM' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
        ]);

        $this->loadMigrationsFrom([
            base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'HRM' . DIRECTORY_SEPARATOR . 'migrations',
        ]);
    }
}
