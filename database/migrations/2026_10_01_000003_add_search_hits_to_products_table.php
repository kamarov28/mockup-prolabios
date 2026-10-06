<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('products') && ! Schema::hasColumn('products', 'search_hits')) {
            Schema::table('products', function (Blueprint $table) {
                $table->unsignedInteger('search_hits')->default(0)->after('is_featured')->index();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('products') && Schema::hasColumn('products', 'search_hits')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('search_hits');
            });
        }
    }
};
