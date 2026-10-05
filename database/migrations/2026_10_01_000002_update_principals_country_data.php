<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $corrections = [
            'KSL Pulse Scientific' => 'Canada',
            'Lumeley' => 'China',
            'Vecverse' => 'China',
            'Vision Med' => 'China',
        ];

        foreach ($corrections as $name => $address) {
            DB::table('principals')
                ->where('name', $name)
                ->update([
                    'address' => $address,
                    'updated_at' => now(),
                ]);
        }
    }

    public function down(): void
    {
        $reverts = [
            'KSL Pulse Scientific' => 'India',
            'Lumeley' => 'United States',
            'Vecverse' => 'Japan',
            'Vision Med' => 'Germany',
        ];

        foreach ($reverts as $name => $address) {
            DB::table('principals')
                ->where('name', $name)
                ->update([
                    'address' => $address,
                    'updated_at' => now(),
                ]);
        }
    }
};
