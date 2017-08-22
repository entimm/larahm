<?php

namespace entimm\LaravelPerfectMoney;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class PerfectMoneyServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__ . '/../config/perfectmoney.php' => config_path('perfectmoney.php'),
        ], 'config');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'perfectmoney');

        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/perfectmoney'),
        ], 'views');

        // Composers
        View::composer('perfectmoney::perfectmoney-form', 'entimm\LaravelPerfectMoney\FormComposer');
    }

    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/perfectmoney.php', 'perfectmoney');

        $this->app->singleton('perfectmoney', function() {
            return new PerfectMoney();
        });
    }
}
