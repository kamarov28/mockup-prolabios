<?php

namespace App\Console\Commands;

use App\Services\DataService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MigrateJsonToDb extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'database:migrate-json {--force : Force the operation to run when in production}';

    /**
     * The console command description.
     */
    protected $description = 'Seed MySQL database from JSON flat-files (one-time migration)';

    protected DataService $dataService;

    public function __construct(DataService $dataService)
    {
        parent::__construct();
        $this->dataService = $dataService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        if (app()->environment('production') && ! $this->option('force')) {
            $this->warn('************************************************');
            $this->warn('*     Application In Production Environment!   *');
            $this->warn('************************************************');

            if (! $this->confirm('Running this command will TRUNCATE existing tables! Do you really wish to run this command?')) {
                $this->info('Command cancelled.');

                return 1;
            }
        }

        $this->info('Starting JSON → MySQL migration...');

        // Test connection
        try {
            DB::connection()->getPdo();
        } catch (\Exception $e) {
            $this->error('Cannot connect to MySQL: '.$e->getMessage());

            return 1;
        }

        // Read from JSON source files
        $sectors = $this->readJson('data/sectors.json');
        $products = $this->readJson('data/products.json');
        $posts = $this->readJson('data/posts.json');
        $homepage = $this->readJson('data/homepage.json', 'assoc');

        $this->info('Found '.count($products).' products');
        $this->info('Found '.count($posts).' posts');
        $this->info('Found '.count($sectors).' sectors');

        // ----- Sectors -----
        DB::table('sectors')->truncate();
        foreach ($sectors as $sec) {
            DB::table('sectors')->insert([
                'id' => $sec['id'],
                'name' => $sec['name'],
                'description' => json_encode($sec['description'] ?? []),
                'image' => $sec['image'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Seeded sectors table.');

        // ----- Products -----
        DB::table('products')->truncate();
        $seenTitles = [];
        foreach ($products as $prod) {
            $title = $prod['title'] ?? '';
            if (! $title || in_array($title, $seenTitles, true)) {
                continue; // skip blank or duplicate titles
            }
            $seenTitles[] = $title;
            DB::table('products')->insertOrIgnore([
                'catalog' => $prod['catalog'] ?? null,
                'title' => $title,
                'description' => $prod['description'] ?? null,
                'category' => $prod['category'],
                'sub_category' => $prod['sub_category'] ?? null,
                'sector' => $prod['sector'] ?? null,
                'image' => $prod['image'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Seeded products table.');

        // ----- Posts -----
        DB::table('posts')->truncate();
        foreach ($posts as $post) {
            DB::table('posts')->insert([
                'slug' => $post['slug'],
                'title' => $post['title'],
                'date' => $post['date'],
                'category' => $post['category'],
                'image' => $post['image'] ?? null,
                'content' => $post['content'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
        $this->info('✓ Seeded posts table.');

        // ----- Homepage Settings -----
        if (! empty($homepage)) {
            DB::table('homepage_settings')->truncate();
            foreach ($homepage as $key => $val) {
                DB::table('homepage_settings')->insert([
                    'key' => $key,
                    'value' => is_array($val) ? json_encode($val) : $val,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->info('✓ Seeded homepage_settings table.');
        } else {
            // Seed defaults if no JSON file exists
            $defaults = $this->dataService->getDefaultHomepageData();
            DB::table('homepage_settings')->truncate();
            foreach ($defaults as $key => $val) {
                DB::table('homepage_settings')->insert([
                    'key' => $key,
                    'value' => is_array($val) ? json_encode($val) : $val,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
            }
            $this->info('✓ Seeded homepage_settings with defaults (no JSON found).');
        }

        $this->info('');
        $this->info('Migration completed successfully! All data is now in MySQL.');

        return 0;
    }

    private function readJson(string $path, string $mode = 'indexed'): array
    {
        $fullPath = storage_path('app/private/'.$path);
        if (! file_exists($fullPath)) {
            $this->warn("File not found: $fullPath — skipping.");

            return [];
        }
        $data = json_decode(file_get_contents($fullPath), true);

        return is_array($data) ? $data : [];
    }
}
