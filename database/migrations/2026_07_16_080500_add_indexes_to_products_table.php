<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $existingIndexes = collect(Schema::getIndexes('products'))->pluck('name')->toArray();

        Schema::table('products', function (Blueprint $table) use ($existingIndexes) {
            if (!in_array('products_category_index', $existingIndexes)) {
                $table->index('category', 'products_category_index');
            }
            if (!in_array('products_sub_category_index', $existingIndexes)) {
                $table->index('sub_category', 'products_sub_category_index');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $existingIndexes = collect(Schema::getIndexes('products'))->pluck('name')->toArray();

        Schema::table('products', function (Blueprint $table) use ($existingIndexes) {
            if (in_array('products_category_index', $existingIndexes)) {
                $table->dropIndex('products_category_index');
            }
            if (in_array('products_sub_category_index', $existingIndexes)) {
                $table->dropIndex('products_sub_category_index');
            }
        });
    }
};
