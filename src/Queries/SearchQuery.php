<?php

namespace Atakujemnie\LaravelVue3Table\Queries;

use Atakujemnie\LaravelVue3Table\Contracts\QueryModifierInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class SearchQuery implements QueryModifierInterface
{
    protected $searchable;

    public function __construct(array $searchable)
    {
        $this->searchable = $searchable;
    }

    public function apply(Builder $query, Request $request = null): void
    {
        $searchTerm = $request->get('searchTerm');
        if ($searchTerm && !empty($this->searchable)) {
            $query->where(function ($subQuery) use ($searchTerm, $query) {
                foreach ($this->searchable as $searchColumn) {
                    $model = $query->getModel();
                    if ($model && Schema::hasColumn($model->getTable(), $searchColumn)) {
                        $subQuery->orWhere($searchColumn, 'like', '%' . $searchTerm . '%');
                    }
                }
            });
        }
    }
}
