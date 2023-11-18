<?php

namespace Atakujemnie\LaravelVue3Table\Contracts;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

interface QueryModifierInterface
{
    public function apply(Builder $query, Request $request = null): void;
}
