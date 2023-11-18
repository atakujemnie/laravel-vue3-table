<?php

namespace Atakujemnie\LaravelVue3Table\Queries;

use Atakujemnie\LaravelVue3Table\Contracts\QueryModifierInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class RelationQuery implements QueryModifierInterface
{
    protected $relations;

    public function __construct(array $relations)
    {
        $this->relations = $relations;
    }

    public function apply(Builder $query, Request $request = null): void
    {
        foreach ($this->relations as $relation) {
            if (method_exists($query->getModel(), $relation)) {
                $query->with($relation);
            }
        }
    }
}
