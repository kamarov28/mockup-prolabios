<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('packaging', 255)->nullable()->after('sub_category');
            $table->text('function')->nullable()->after('packaging');
            $table->string('reference_method', 500)->nullable()->after('function');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['packaging', 'function', 'reference_method']);
        });
    }
};
