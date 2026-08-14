<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('posts')) {
            $posts = DB::table('posts')->get();
            foreach ($posts as $post) {
                if (! empty($post->date)) {
                    $timestamp = strtotime($post->date);
                    if ($timestamp !== false) {
                        $isoDate = date('Y-m-d', $timestamp);
                        DB::table('posts')->where('id', $post->id)->update(['date' => $isoDate]);
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No reverse needed for date format normalization
    }
};
