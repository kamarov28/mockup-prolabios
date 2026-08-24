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
        if (! Schema::hasTable('posts')) {
            return;
        }

        $indoMonths = [
            'Januari' => 'January', 'Jan' => 'Jan',
            'Februari' => 'February', 'Feb' => 'Feb',
            'Maret' => 'March', 'Mar' => 'Mar',
            'April' => 'April', 'Apr' => 'Apr',
            'Mei' => 'May',
            'Juni' => 'June', 'Jun' => 'Jun',
            'Juli' => 'July', 'Jul' => 'Jul',
            'Agustus' => 'August', 'Agu' => 'Aug', 'Agt' => 'Aug',
            'September' => 'September', 'Sep' => 'Sep',
            'Oktober' => 'October', 'Okt' => 'Oct',
            'November' => 'November', 'Nov' => 'Nov',
            'Desember' => 'December', 'Des' => 'Dec',
        ];

        $posts = DB::table('posts')->get();
        foreach ($posts as $post) {
            if (empty($post->date)) {
                continue;
            }

            try {
                $raw = (string) $post->date;
                $normalized = str_ireplace(array_keys($indoMonths), array_values($indoMonths), $raw);
                $carbon = \Carbon\Carbon::parse($normalized);
                $isoDate = $carbon->format('Y-m-d');

                DB::table('posts')->where('id', $post->id)->update(['date' => $isoDate]);
            } catch (\Throwable $e) {
                // If unparseable, leave as is
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
