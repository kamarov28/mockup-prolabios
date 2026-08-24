<?php

namespace App\Traits;

use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;

/**
 * PaginatesQuery
 *
 * Reusable manual pagination helper for raw DB::table() query builders.
 * Eliminates the copy-pasted pagination block shared between
 * AdminProductController and AdminPostController.
 */
trait PaginatesQuery
{
    /**
     * Apply clamped manual pagination to a query builder (Eloquent or Query Builder)
     * and return the paginated rows + metadata needed by the view.
     *
     * @param  \Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder  $query
     * @param  Request  $request  Current HTTP request (reads ?page=)
     * @param  int      $perPage  Rows per page
     * @return array{items: array, currentPage: int, totalPages: int}
     */
    protected function paginateQuery(\Illuminate\Database\Query\Builder|\Illuminate\Database\Eloquent\Builder $query, Request $request, int $perPage): array
    {
        $builder = $query instanceof \Illuminate\Database\Eloquent\Builder ? $query->toBase() : $query;

        $total      = $builder->count();
        $totalPages = max(1, (int) ceil($total / $perPage));

        $currentPage = (int) $request->input('page', 1);
        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $perPage;
        $items  = $builder->skip($offset)->take($perPage)->get()->map(fn ($r) => (array) $r)->toArray();

        return compact('items', 'currentPage', 'totalPages');
    }
}
