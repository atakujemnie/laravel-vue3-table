<?php

namespace Atakujemnie\LaravelVue3Table;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Model;

abstract class TableService
{
    protected Model $model;
    protected array $columns = [];
    protected array $searchable = [];
    protected array $sortable = [];
    protected array $excludedColumns = [];
    protected ?string $defaultSortColumn = null;
    protected string $defaultSortDirection = 'asc';
    protected array $relations = [];
    protected array $additionalColumns = [];
    protected array $hiddenColumns = [];
    protected array $columnOrder = [];

    abstract protected function setModel(): void;

    public function __construct()
    {
        $this->setModel();
        $this->setRelations();
        $this->setExcludedColumns();
        $this->setSearchable();
        $this->setSortable();
        $this->setDefaultSorting();
        $this->getAdditionalColumns();
        $this->setHiddenColumns();
        $this->initializeColumns();
    }

    protected function setRelations(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setSearchable(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setSortable(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setExcludedColumns(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setDefaultSorting(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function getAdditionalColumns(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setHiddenColumns(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function setColumnOrder(): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function applyCustomQueryConditions(Builder $query, Request $request): void
    {
        // Opcjonalna implementacja w klasie dziedziczącej
    }

    protected function initializeColumns(): void
    {
        $columnService = new ColumnService($this->model, [
            'relations' => $this->relations,
            'additionalColumns' => $this->additionalColumns,
            'hiddenColumns' => $this->hiddenColumns,
            'excludedColumns' => $this->excludedColumns,
            'sortable' => $this->sortable,
            'searchable' => $this->searchable,
            'columnOrder' => $this->columnOrder,
        ]);

        $this->columns = $columnService->initializeColumns();
    }

    public function getTableData(Request $request): array
    {
        $query = $this->buildQuery($request);
        $data = $this->pagination($query, $request);

        $items = collect($data->items())->map(function ($item) {
            foreach ($this->additionalColumns as $additionalColumn) {
                if (isset($additionalColumn['contentQuery'])) {
                    $method = $additionalColumn['contentQuery'];
                    $item->{$additionalColumn['name']} = call_user_func($method, $item->id);
                }
            }
            return $item;
        });

        return [
            'data' => $items,
            'columns' => array_values($this->columns),
            'pagination' => [
                'current_page' => $data->currentPage(),
                'total_pages' => $data->lastPage(),
            ],
        ];
    }

    protected function buildQuery(Request $request): Builder
    {
        $columnsToLoad = array_diff($this->model->getFillable(), $this->excludedColumns);

        $query = $this->model::select($columnsToLoad);
        $this->applyCustomQueryConditions($query, $request);
        $this->applySorting($query, $request);
        $this->applySearch($query, $request);
        $this->applyRelations($query);

        return $query;
    }

    protected function pagination(Builder $query, Request $request)
    {
        $perPage = $request->get('perPage', 10);
        $page = $request->get('page', 1);
        $total = $query->count();

        $totalPages = ceil($total / $perPage);
        if ($page > $totalPages) {
            $page = 1;
        }

        return $query->paginate($perPage, ['*'], 'page', $page);
    }

    protected function applySorting(Builder $query, Request $request): void
    {
        $sortQuery = new Queries\SortQuery($this->sortable, $this->defaultSortColumn, $this->defaultSortDirection);
        $sortQuery->apply($query, $request);
    }

    protected function applySearch(Builder $query, Request $request): void
    {
        $searchQuery = new Queries\SearchQuery($this->searchable);
        $searchQuery->apply($query, $request);
    }

    protected function applyRelations(Builder $query): void
    {
        $relationQuery = new Queries\RelationQuery($this->relations);
        $relationQuery->apply($query, null);
    }
}
