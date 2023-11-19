<?php

namespace Atakujemnie\LaravelVue3Table;

use Illuminate\Support\ServiceProvider;
use Atakujemnie\LaravelVue3Table\Console\Commands\MakeTableCommand;
use Atakujemnie\LaravelVue3Table\Console\Commands\CopyVueComponentCommand;
use Atakujemnie\LaravelVue3Table\TableService;

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
            __DIR__ . '/../resources/js/components/LaravelVueTable/Table.vue' => resource_path('js/components/LaravelVueTable/Table.vue'),
            __DIR__ . '/../resources/js/LaravelVueTable/components/TableElements/TableSortIcon.vue' => resource_path('js/components/LaravelVueTable/TableElements/TableSortIcon.vue'),
        ], 'laravel-vue3-table-components');
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(TableService::class, function ($app) {
            return new TableService();
        });
    }
}
