<?php

namespace Atakujemnie\LaravelVue3Table\Queries;

use Atakujemnie\LaravelVue3Table\Contracts\QueryModifierInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class SortQuery implements QueryModifierInterface
{
    protected $sortable;
    protected $defaultSortColumn;
    protected $defaultSortDirection;

    public function __construct(array $sortable, ?string $defaultSortColumn, string $defaultSortDirection)
    {
        $this->sortable = $sortable;
        $this->defaultSortColumn = $defaultSortColumn;
        $this->defaultSortDirection = $defaultSortDirection;
    }

    public function apply(Builder $query, Request $request = null): void
    {
        $sortColumn = $request->get('sortColumn', $this->defaultSortColumn);
        $sortDirection = $request->get('sortDirection', $this->defaultSortDirection) === 'desc' ? 'desc' : 'asc';

        if ($sortColumn && in_array($sortColumn, $this->sortable)) {
            $query->orderBy($sortColumn, $sortDirection);
        }
    }
}
