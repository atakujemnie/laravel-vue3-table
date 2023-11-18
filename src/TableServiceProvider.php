<?php

namespace Atakujemnie\LaravelVue3Table;

use Illuminate\Support\ServiceProvider;
use Atakujemnie\LaravelVue3Table\Console\Commands\MakeTableCommand;
use Atakujemnie\LaravelVue3Table\Console\Commands\CopyVueComponentCommand;

class TableServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                MakeTableCommand::class,
                CopyVueComponentCommand::class,

            ]);
        }
        // Publikuj komponent Vue, aby był dostępny w aplikacji
        $this->publishes([
            __DIR__ . '/../resources/js/components/ApiTable.vue' => resource_path('js/components/ApiTable.vue'),
        ], 'laravel-vue3-table-components');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        // Tutaj możesz zarejestrować dowolne usługi, jeśli są potrzebne
    }
}
