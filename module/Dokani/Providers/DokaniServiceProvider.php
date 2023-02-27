<?php

namespace Module\Dokani\Providers;

use Illuminate\Support\ServiceProvider;

class DokaniServiceProvider extends ServiceProvider
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
            base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Dokani' . DIRECTORY_SEPARATOR . 'database' . DIRECTORY_SEPARATOR . 'migrations',
        ]);

        $this->loadMigrationsFrom([
            base_path() . DIRECTORY_SEPARATOR . 'module' . DIRECTORY_SEPARATOR . 'Dokani' . DIRECTORY_SEPARATOR . 'migrations',
        ]);
    }
}
