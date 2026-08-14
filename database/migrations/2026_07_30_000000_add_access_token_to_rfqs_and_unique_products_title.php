<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Add access_token to rfqs table to prevent IDOR attacks.
     * Also add unique index to products.title required for upsert().
     */
    public function up(): void
    {
        // Add access_token to rfqs table
        Schema::table('rfqs', function (Blueprint $table) {
            $table->string('access_token', 64)->nullable()->after('rfq_number');
            $table->index('access_token', 'rfqs_access_token_index');
        });

        // Populate existing RFQ records with a generated token
        $rfqs = DB::table('rfqs')->whereNull('access_token')->get();
        foreach ($rfqs as $rfq) {
            DB::table('rfqs')
                ->where('id', $rfq->id)
                ->update(['access_token' => Str::random(48)]);
        }

        // Add unique index on products.title (required for upsert() to work correctly)
        // Skip if index already exists
        try {
            Schema::table('products', function (Blueprint $table) {
                // Drop the old non-unique title index first if it exists, then add unique
                $sm = Schema::getConnection()->getDoctrineSchemaManager();
                $t = $sm->introspectTable('products');
                if ($t->hasIndex('products_title_index')) {
                    $table->dropIndex('products_title_index');
                }
                if (! $t->hasIndex('products_title_unique')) {
                    $table->unique('title', 'products_title_unique');
                }
            });
        } catch (Exception $e) {
            Log::warning('products title unique index: '.$e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->dropIndex('rfqs_access_token_index');
            $table->dropColumn('access_token');
        });

        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique('products_title_unique');
                $table->index('title', 'products_title_index');
            });
        } catch (Exception $e) {
            // Silently skip
        }
    }
};
