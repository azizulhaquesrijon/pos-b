<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('*', function ($view) {

            if (Auth::check()) {

                if (!session()->has('slugs')) {
                    session()->put('slugs', auth()->user()->permissions()->pluck('slug')->toArray());
                }

                // share data
                view()->share([
                    'slugs'             => (session()->get('slugs') ?? []),
                ]);

                // forget or remove permission data
                if ($view->getName() == 'partials._footer') {
                    session()->forget('slugs');
                }
            } else {
                view()->share(['slugs' => []]);
            }
        });
    }
}
