<?php

namespace App\Services;

use App\Models\Sector;
use Illuminate\Support\Facades\Cache;

class SectorService
{
    public function getSectors(): array
    {
        return Cache::remember('sectors_list_v2', 3600, function () {
            return Sector::orderBy('name')
                ->get()
                ->map(fn (Sector $s) => $this->toArray($s))
                ->toArray();
        });
    }

    public function getSectorById(string $id): ?array
    {
        $sector = Sector::find($id);

        return $sector ? $this->toArray($sector) : null;
    }

    public function addSector(array $sector): bool
    {
        Sector::create([
            'id'          => $sector['id'],
            'name'        => $sector['name'],
            'description' => is_array($sector['description'] ?? null)
                ? $sector['description']
                : (json_decode($sector['description'] ?? '[]', true) ?? []),
            'image'       => $sector['image'] ?? null,
        ]);

        Cache::forget('sectors_list_v2');

        return true;
    }

    public function updateSector(string $id, array $updatedSector): bool
    {
        $sector = Sector::find($id);
        if (! $sector) {
            return false;
        }

        $sector->update([
            'name'        => $updatedSector['name'],
            'description' => is_array($updatedSector['description'] ?? null)
                ? $updatedSector['description']
                : (json_decode($updatedSector['description'] ?? '[]', true) ?? []),
            'image'       => $updatedSector['image'] ?? null,
        ]);

        Cache::forget('sectors_list_v2');

        return true;
    }

    public function deleteSector(string $id): bool
    {
        $sector = Sector::find($id);
        if (! $sector) {
            return false;
        }

        $deleted = $sector->delete();
        Cache::forget('sectors_list_v2');

        return (bool) $deleted;
    }

    private function toArray(Sector $sector): array
    {
        $desc = $sector->description;
        if (is_string($desc)) {
            $desc = json_decode($desc, true) ?? [];
        }

        return [
            'id'          => $sector->id,
            'name'        => $sector->name,
            'description' => is_array($desc) ? $desc : [],
            'image'       => $sector->image,
            'created_at'  => optional($sector->created_at)?->toDateTimeString(),
            'updated_at'  => optional($sector->updated_at)?->toDateTimeString(),
        ];
    }
}

