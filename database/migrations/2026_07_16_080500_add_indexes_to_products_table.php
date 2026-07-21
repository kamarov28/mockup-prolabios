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
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('category', 'products_category_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, skip gracefully
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->index('sub_category', 'products_sub_category_index');
            });
        } catch (\Exception $e) {
            // Index might already exist, skip gracefully
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_category_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, skip gracefully
        }

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropIndex('products_sub_category_index');
            });
        } catch (\Exception $e) {
            // Index might not exist, skip gracefully
        }
    }
};
