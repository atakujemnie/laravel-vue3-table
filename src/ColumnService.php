<?php

namespace Atakujemnie\LaravelVue3Table;

use Illuminate\Database\Eloquent\Model;

class ColumnService
{
    protected Model $model;
    protected array $relations = [];
    protected array $additionalColumns = [];
    protected array $hiddenColumns = [];
    protected array $excludedColumns = [];
    protected array $sortable = [];
    protected array $searchable = [];
    protected array $columnOrder = [];

    public function __construct(Model $model, array $config)
    {
        $this->model = $model;
        $this->relations = $config['relations'] ?? [];
        $this->additionalColumns = $config['additionalColumns'] ?? [];
        $this->hiddenColumns = $config['hiddenColumns'] ?? [];
        $this->excludedColumns = $config['excludedColumns'] ?? [];
        $this->sortable = $config['sortable'] ?? [];
        $this->searchable = $config['searchable'] ?? [];
        $this->columnOrder = $config['columnOrder'] ?? [];
    }

    public function initializeColumns(): array
    {
        $allColumns = $this->gatherAllColumns();
        $allColumns = $this->filterColumns($allColumns);
        $allColumns = $this->configureColumns($allColumns);

        if (!empty($this->columnOrder)) {
            $allColumns = $this->sortColumns($allColumns);
        }

        return array_values($allColumns);
    }

    protected function gatherAllColumns(): array
    {
        $allColumns = [];

        foreach ($this->model->getFillable() as $column) {
            $allColumns[$column] = ['name' => $column, 'label' => ucfirst($column)];
        }

        foreach ($this->relations as $relation) {
            $allColumns[$relation] = ['name' => $relation, 'label' => ucfirst($relation), 'relation' => true];
        }

        foreach ($this->additionalColumns as $additionalColumn) {
            $allColumns[$additionalColumn['name']] = $additionalColumn;
        }

        return $allColumns;
    }

    protected function filterColumns(array $columns): array
    {
        return array_filter($columns, function ($key) {
            return !in_array($key, $this->hiddenColumns) && !in_array($key, $this->excludedColumns);
        }, ARRAY_FILTER_USE_KEY);
    }

    protected function configureColumns(array $columns): array
    {
        foreach ($columns as $key => &$column) {
            $column['sortable'] = in_array($key, $this->sortable);
            $column['searchable'] = in_array($key, $this->searchable);
            $customConfig = $this->getColumnConfiguration($key) ?? [];
            $additionalConfig = $this->findInAdditionalColumns($key) ?? [];
            $column = array_merge($column, $customConfig, $additionalConfig);
        }
        return $columns;
    }

    protected function sortColumns(array $columns): array
    {
        $sortedColumns = [];
        foreach ($this->columnOrder as $columnName) {
            if (array_key_exists($columnName, $columns)) {
                $sortedColumns[$columnName] = $columns[$columnName];
            }
        }
        return $sortedColumns;
    }

    protected function getColumnConfiguration(string $columnName): ?array
    {
        // Opcjonalna implementacja w klasie dziedziczącej
        return null;
    }

    protected function findInAdditionalColumns(string $columnName): ?array
    {
        foreach ($this->additionalColumns as $additionalColumn) {
            if ($additionalColumn['name'] === $columnName) {
                return $additionalColumn;
            }
        }
        return null;
    }

    // Tutaj możesz dodać inne metody, które są specyficzne dla Twojej implementacji
}
