<?php

namespace Atakujemnie\LaravelVue3Table\Tests\Unit;

use Atakujemnie\LaravelVue3Table\Tests\TestCase;
use Atakujemnie\LaravelVue3Table\Queries\SortQuery;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Mockery;

class SortQueryTest extends TestCase
{
    /** @test */
    public function it_applies_sort_to_query()
    {
        $request = new Request(['sortColumn' => 'column1', 'sortDirection' => 'asc']);
        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('orderBy')->once()->with('column1', 'asc');

        $defaultSortColumn = 'column1';
        $defaultSortDirection = 'asc';

        $sortQuery = new SortQuery(['column1', 'column2'], $defaultSortColumn, $defaultSortDirection);
        $sortQuery->apply($query, $request);
    }
}
