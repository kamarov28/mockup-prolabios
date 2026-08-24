<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            if (! Schema::hasColumn('products', 'slug')) {
                $table->string('slug', 255)->nullable()->after('title');
            }
        });

        // Backfill unique slugs from title
        $used = [];
        DB::table('products')->orderBy('id')->chunkById(100, function ($rows) use (&$used) {
            foreach ($rows as $row) {
                if (! empty($row->slug)) {
                    $used[$row->slug] = true;

                    continue;
                }

                $base = Str::slug((string) $row->title);
                if ($base === '') {
                    $base = 'product-'.$row->id;
                }

                $slug = $base;
                $i = 2;
                while (isset($used[$slug]) || DB::table('products')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                    $slug = $base.'-'.$i;
                    $i++;
                }

                $used[$slug] = true;
                DB::table('products')->where('id', $row->id)->update(['slug' => $slug]);
            }
        });

        // Unique index (nullable rows already filled)
        Schema::table('products', function (Blueprint $table) {
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            // Prefer try/catch for drivers without doctrine
            try {
                $table->unique('slug', 'products_slug_unique');
            } catch (\Throwable $e) {
                // already exists
            }
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            try {
                $table->dropUnique('products_slug_unique');
            } catch (\Throwable $e) {
            }
            if (Schema::hasColumn('products', 'slug')) {
                $table->dropColumn('slug');
            }
        });
    }
};
