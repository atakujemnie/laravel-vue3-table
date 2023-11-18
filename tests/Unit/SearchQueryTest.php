<?php

namespace Atakujemnie\LaravelVue3Table\Tests\Unit;

use Atakujemnie\LaravelVue3Table\Tests\TestCase;

use Atakujemnie\LaravelVue3Table\Queries\SearchQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Mockery;

class SearchQueryTest extends TestCase
{
    /** @test */
    public function it_applies_search_to_query()
    {
        $request = new Request(['searchTerm' => 'test']);
        $query = Mockery::mock(Builder::class);

        // Symulacja dla wywołania 'getModel'
        $model = Mockery::mock();
        $model->shouldReceive('getTable')->andReturn('table_name');
        $query->shouldReceive('getModel')->andReturn($model);

        // Symulacja dla wywołania 'where' z funkcją anonimową
        $query->shouldReceive('where')
            ->once()
            ->with(Mockery::on(function ($argument) {
                return is_callable($argument);
            }));

        $searchQuery = new SearchQuery(['column1', 'column2', 'column3']);
        $searchQuery->apply($query, $request);
    }
}
