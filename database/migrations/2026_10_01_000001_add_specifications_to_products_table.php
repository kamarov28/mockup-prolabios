<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'packaging')) {
                $table->string('packaging', 255)->nullable()->after('sub_category');
            }
            if (! Schema::hasColumn('products', 'function')) {
                $table->text('function')->nullable()->after('packaging');
            }
            if (! Schema::hasColumn('products', 'reference_method')) {
                $table->string('reference_method', 500)->nullable()->after('function');
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['packaging', 'function', 'reference_method']);
        });
    }
};
