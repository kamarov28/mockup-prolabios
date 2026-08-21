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
     * Apply clamped manual pagination to a raw query builder and return
     * the paginated rows + metadata needed by the view.
     *
     * @param  Builder  $query    The query builder (already filtered/sorted)
     * @param  Request  $request  Current HTTP request (reads ?page=)
     * @param  int      $perPage  Rows per page
     * @return array{items: array, currentPage: int, totalPages: int}
     */
    protected function paginateQuery(Builder $query, Request $request, int $perPage): array
    {
        $total      = $query->count();
        $totalPages = max(1, (int) ceil($total / $perPage));

        $currentPage = (int) $request->input('page', 1);
        $currentPage = max(1, min($currentPage, $totalPages));

        $offset = ($currentPage - 1) * $perPage;
        $items  = $query->skip($offset)->take($perPage)->get()->map(fn ($r) => (array) $r)->toArray();

        return compact('items', 'currentPage', 'totalPages');
    }
}
