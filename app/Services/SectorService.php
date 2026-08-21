<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SectorService
{
    public function getSectors(): array
    {
        return Cache::remember('sectors_list_v2', 3600, function () {
            return DB::table('sectors')
                ->orderBy('name')
                ->get()
                ->map(function ($r) {
                    $row = (array) $r;
                    $row['description'] = is_string($row['description'])
                        ? (json_decode($row['description'], true) ?? [])
                        : ($row['description'] ?? []);

                    return $row;
                })
                ->toArray();
        });
    }

    public function getSectorById(string $id): ?array
    {
        $row = DB::table('sectors')->where('id', $id)->first();
        if (! $row) {
            return null;
        }
        $row = (array) $row;
        $row['description'] = is_string($row['description'])
            ? (json_decode($row['description'], true) ?? [])
            : ($row['description'] ?? []);

        return $row;
    }

    public function addSector(array $sector): bool
    {
        DB::table('sectors')->insert([
            'id'          => $sector['id'],
            'name'        => $sector['name'],
            'description' => json_encode($sector['description'] ?? []),
            'image'       => $sector['image'] ?? null,
            'created_at'  => now(),
            'updated_at'  => now(),
        ]);
        Cache::forget('sectors_list_v2');

        return true;
    }

    public function updateSector(string $id, array $updatedSector): bool
    {
        DB::table('sectors')->where('id', $id)->update([
            'name'        => $updatedSector['name'],
            'description' => json_encode($updatedSector['description'] ?? []),
            'image'       => $updatedSector['image'] ?? null,
            'updated_at'  => now(),
        ]);
        Cache::forget('sectors_list_v2');

        return true;
    }

    public function deleteSector(string $id): bool
    {
        DB::table('sectors')->where('id', $id)->delete();
        Cache::forget('sectors_list_v2');

        return true;
    }
}
