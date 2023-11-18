<?php

namespace Atakujemnie\LaravelVue3Table\Tests\Feature;

use Atakujemnie\LaravelVue3Table\Tests\TestCase;

class MakeTableCommandTest extends TestCase
{
    /** @test */
    public function it_creates_a_new_table_class()
    {
        $className = 'TestTable';
        $modelName = 'TestModel'; // Przykładowa nazwa modelu, dostosuj ją do swoich potrzeb

        $this->artisan('make:table', ['name' => $className, 'model' => $modelName])
            ->assertExitCode(0);

        $this->assertTrue(file_exists(app_path("Tables/{$className}.php")));

        // Opcjonalnie: Usuń plik po teście
        @unlink(app_path("Tables/{$className}.php"));
    }
}
