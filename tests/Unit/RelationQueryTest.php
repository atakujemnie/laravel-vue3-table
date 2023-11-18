<?php

namespace Atakujemnie\LaravelVue3Table\Tests\Unit;

use Atakujemnie\LaravelVue3Table\Tests\TestCase;
use Atakujemnie\LaravelVue3Table\Queries\RelationQuery;
use Illuminate\Database\Eloquent\Builder;
use Mockery;

class RelationQueryTest extends TestCase
{
    /** @test */
    public function it_applies_relations_to_query()
    {
        $query = Mockery::mock(Builder::class);
        $query->shouldReceive('getModel')->andReturn(new class
        {
            public function relation1()
            {
            }
            public function relation2()
            {
            }
        });
        $query->shouldReceive('with')->once()->with('relation1');
        $query->shouldReceive('with')->once()->with('relation2');

        $relationQuery = new RelationQuery(['relation1', 'relation2']);
        $relationQuery->apply($query);
    }
}
