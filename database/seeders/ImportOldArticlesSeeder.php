<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ImportOldArticlesSeeder extends Seeder
{
    public function run(): void
    {
        $sqlPath = base_path('prolabio_web.sql');
        if (! file_exists($sqlPath)) {
            $this->command->error('File prolabio_web.sql tidak ditemukan di root project.');

            return;
        }

        $sqlContent = file_get_contents($sqlPath);

        // Extract INSERT statements for web_content
        preg_match_all('/INSERT INTO `web_content` [^;]+;/s', $sqlContent, $insertMatches);

        if (empty($insertMatches[0])) {
            $this->command->warn('Tidak ada data web_content yang ditemukan di SQL.');

            return;
        }

        $imported = 0;

        foreach ($insertMatches[0] as $insertQuery) {
            preg_match_all("/\(([^()]+(?:\([^()]*\)[^()]*)*)\)/", $insertQuery, $rows);

            if (empty($rows[1])) {
                continue;
            }

            foreach ($rows[1] as $rowStr) {
                // Split SQL values
                $tokens = str_getcsv($rowStr, ',', "'", '\\');

                if (count($tokens) < 13) {
                    continue;
                }

                $contentId = trim($tokens[0]);
                if (! is_numeric($contentId)) {
                    continue;
                }
                $title = trim($tokens[3]);
                $description = trim($tokens[5]);
                $imageFile = trim($tokens[7]);
                $posted = trim($tokens[9]);
                $statusNum = (int) trim($tokens[11]);
                $published = trim($tokens[12]);
                $type = isset($tokens[13]) ? trim($tokens[13]) : 'blog';

                // Skip non-blog types or poster placeholder rows
                if (empty($title) || $title === 'Poster Image' || empty($description)) {
                    continue;
                }

                $status = ($statusNum === 1) ? 'online' : 'draft';

                // Format published date
                $dateStr = (! empty($published) && $published !== '0000-00-00 00:00:00')
                    ? date('Y-m-d', strtotime($published))
                    : (! empty($posted) ? date('Y-m-d', strtotime($posted)) : date('Y-m-d'));

                $formattedDate = date('d/m/Y', strtotime($dateStr));

                $slug = Str::slug($title);
                if (empty($slug)) {
                    $slug = 'post-'.$contentId;
                }

                // Check slug uniqueness
                $existing = DB::table('posts')->where('slug', $slug)->first();
                if ($existing) {
                    $slug .= '-'.$contentId;
                }

                // Format image URL
                $imageUrl = 'https://images.unsplash.com/photo-1540575467063-178a50c2df87?auto=format&fit=crop&w=600&q=80';
                if (! empty($imageFile)) {
                    $imageUrl = 'https://www.prolabios.com/content/photo/'.$imageFile;
                }

                DB::table('posts')->updateOrInsert(
                    ['title' => $title],
                    [
                        'slug' => $slug,
                        'title' => $title,
                        'date' => $formattedDate,
                        'category' => 'Berita & Kegiatan',
                        'status' => $status,
                        'image' => $imageUrl,
                        'content' => $description,
                        'created_at' => ! empty($posted) ? $posted : now(),
                        'updated_at' => now(),
                    ]
                );

                $imported++;
            }
        }

        $this->command->info("Berhasil mengimpor {$imported} artikel lama dari database prolabio_web.sql.");
    }
}
