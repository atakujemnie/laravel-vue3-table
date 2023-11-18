<?php

namespace Atakujemnie\LaravelVue3Table\Tests;

use Orchestra\Testbench\TestCase as OrchestraTestCase;

class TestCase extends OrchestraTestCase
{
    protected function getPackageProviders($app)
    {
        return ['Atakujemnie\LaravelVue3Table\TableServiceProvider'];
    }

    // Tutaj możesz dodać dodatkowe metody konfiguracyjne, jeśli są potrzebne
}
