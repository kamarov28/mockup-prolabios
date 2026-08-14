<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (! Schema::hasTable('product_sector')) {
            Schema::create('product_sector', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('product_id');
                $table->string('sector_id');
                $table->timestamps();

                $table->index('product_id');
                $table->index('sector_id');
            });
        }

        // Migrate existing comma-separated sector strings to product_sector pivot table
        if (Schema::hasTable('products')) {
            $products = DB::table('products')->get();
            foreach ($products as $p) {
                if (! empty($p->sector)) {
                    $sectorIds = array_filter(array_map('trim', explode(',', $p->sector)));
                    foreach ($sectorIds as $secId) {
                        if (! empty($secId)) {
                            DB::table('product_sector')->updateOrInsert([
                                'product_id' => $p->id,
                                'sector_id' => $secId,
                            ], [
                                'created_at' => now(),
                                'updated_at' => now(),
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_sector');
    }
};
