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
        if (! Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->string('slug', 255)->nullable()->after('title');
            });
        }

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
                while (isset($used[$slug])) {
                    $slug = $base.'-'.$i;
                    $i++;
                }

                // Also avoid colliding with any existing DB row not in this chunk yet
                while (DB::table('products')->where('slug', $slug)->where('id', '!=', $row->id)->exists()) {
                    $slug = $base.'-'.$i;
                    $i++;
                }

                $used[$slug] = true;
                DB::table('products')->where('id', $row->id)->update(['slug' => $slug]);
            }
        });

        // Unique index — skip if already present
        $indexExists = false;
        try {
            $sm = Schema::getConnection()->select("SHOW INDEX FROM products WHERE Key_name = 'products_slug_unique'");
            $indexExists = ! empty($sm);
        } catch (\Throwable $e) {
            // SQLite / other: try creating and ignore duplicate
        }

        if (! $indexExists) {
            try {
                Schema::table('products', function (Blueprint $table) {
                    $table->unique('slug', 'products_slug_unique');
                });
            } catch (\Throwable $e) {
                // Index may already exist under another name
            }
        }
    }

    public function down(): void
    {
        try {
            Schema::table('products', function (Blueprint $table) {
                $table->dropUnique('products_slug_unique');
            });
        } catch (\Throwable $e) {
        }

        if (Schema::hasColumn('products', 'slug')) {
            Schema::table('products', function (Blueprint $table) {
                $table->dropColumn('slug');
            });
        }
    }
};
