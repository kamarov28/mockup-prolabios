<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasIndex('products', 'products_featured_hits_id_index')) {
                $table->index(['is_featured', 'search_hits', 'id'], 'products_featured_hits_id_index');
            }
        });

        Schema::table('rfqs', function (Blueprint $table) {
            if (! Schema::hasIndex('rfqs', 'rfqs_status_created_at_index')) {
                $table->index(['status', 'created_at'], 'rfqs_status_created_at_index');
            }
        });

        Schema::table('product_sector', function (Blueprint $table) {
            if (! Schema::hasIndex('product_sector', 'product_sector_sector_product_index')) {
                $table->index(['sector_id', 'product_id'], 'product_sector_sector_product_index');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasIndex('products', 'products_featured_hits_id_index')) {
                $table->dropIndex('products_featured_hits_id_index');
            }
        });

        Schema::table('rfqs', function (Blueprint $table) {
            if (Schema::hasIndex('rfqs', 'rfqs_status_created_at_index')) {
                $table->dropIndex('rfqs_status_created_at_index');
            }
        });

        Schema::table('product_sector', function (Blueprint $table) {
            if (Schema::hasIndex('product_sector', 'product_sector_sector_product_index')) {
                $table->dropIndex('product_sector_sector_product_index');
            }
        });
    }
};
