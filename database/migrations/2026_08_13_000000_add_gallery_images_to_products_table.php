<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Adds a `gallery_images` JSON column to store multiple additional product
     * photos (beyond the single primary/cover `image` column used for the
     * catalog card thumbnail, OG meta tags, cart, and quotation PDF).
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (!Schema::hasColumn('products', 'gallery_images')) {
                $table->json('gallery_images')->nullable()->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (Schema::hasColumn('products', 'gallery_images')) {
                $table->dropColumn('gallery_images');
            }
        });
    }
};
