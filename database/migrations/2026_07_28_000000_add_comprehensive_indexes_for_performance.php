<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Adds comprehensive indexes for high-traffic performance optimization.
     */
    public function up(): void
    {
        // Products table - critical for search and filtering performance
        try {
            Schema::table('products', function (Blueprint $table) {
                // Single column indexes
                if (!$this->hasIndex('products', 'products_title_index')) {
                    $table->index('title', 'products_title_index');
                }
                if (!$this->hasIndex('products', 'products_description_index')) {
                    $table->index('description', 'products_description_index');
                }
                if (!$this->hasIndex('products', 'products_price_index')) {
                    $table->index('price', 'products_price_index');
                }
                if (!$this->hasIndex('products', 'products_stock_index')) {
                    $table->index('stock', 'products_stock_index');
                }
                if (!$this->hasIndex('products', 'products_sector_index')) {
                    $table->index('sector', 'products_sector_index');
                }
                
                // Composite indexes for common query patterns
                if (!$this->hasIndex('products', 'products_category_subcategory_index')) {
                    $table->index(['category', 'sub_category'], 'products_category_subcategory_index');
                }
                if (!$this->hasIndex('products', 'products_category_sector_index')) {
                    $table->index(['category', 'sector'], 'products_category_sector_index');
                }
                if (!$this->hasIndex('products', 'products_search_index')) {
                    $table->index(['title', 'category'], 'products_search_index');
                }
            });
        } catch (\Exception $e) {
            // Log error but continue
            \Illuminate\Support\Facades\Log::warning('Products indexing failed: ' . $e->getMessage());
        }

        // Posts table - for blog/news listing performance
        try {
            Schema::table('posts', function (Blueprint $table) {
                if (!$this->hasIndex('posts', 'posts_category_index')) {
                    $table->index('category', 'posts_category_index');
                }
                if (!$this->hasIndex('posts', 'posts_slug_index')) {
                    $table->index('slug', 'posts_slug_index');
                }
                if (!$this->hasIndex('posts', 'posts_status_index')) {
                    $table->index('status', 'posts_status_index');
                }
                if (!$this->hasIndex('posts', 'posts_created_at_index')) {
                    $table->index('created_at', 'posts_created_at_index');
                }
                
                // Composite index for filtered listings
                if (!$this->hasIndex('posts', 'posts_category_status_index')) {
                    $table->index(['category', 'status', 'created_at'], 'posts_category_status_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Posts indexing failed: ' . $e->getMessage());
        }

        // Sectors table
        try {
            Schema::table('sectors', function (Blueprint $table) {
                if (Schema::hasColumn('sectors', 'name') && !$this->hasIndex('sectors', 'sectors_name_index')) {
                    $table->index('name', 'sectors_name_index');
                }
                if (Schema::hasColumn('sectors', 'slug') && !$this->hasIndex('sectors', 'sectors_slug_index')) {
                    $table->index('slug', 'sectors_slug_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Sectors indexing failed: ' . $e->getMessage());
        }

        // RFQ tables - critical for procurement workflow
        try {
            Schema::table('rfqs', function (Blueprint $table) {
                if (Schema::hasColumn('rfqs', 'user_id') && !$this->hasIndex('rfqs', 'rfqs_user_id_index')) {
                    $table->index('user_id', 'rfqs_user_id_index');
                }
                if (Schema::hasColumn('rfqs', 'status') && !$this->hasIndex('rfqs', 'rfqs_status_index')) {
                    $table->index('status', 'rfqs_status_index');
                }
                if (Schema::hasColumn('rfqs', 'created_at') && !$this->hasIndex('rfqs', 'rfqs_created_at_index')) {
                    $table->index('created_at', 'rfqs_created_at_index');
                }
                
                // Composite for user's RFQs by status
                if (Schema::hasColumn('rfqs', 'user_id') && Schema::hasColumn('rfqs', 'status') && !$this->hasIndex('rfqs', 'rfqs_user_status_index')) {
                    $table->index(['user_id', 'status'], 'rfqs_user_status_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('RFQs indexing failed: ' . $e->getMessage());
        }

        try {
            Schema::table('rfq_items', function (Blueprint $table) {
                if (Schema::hasColumn('rfq_items', 'rfq_id') && !$this->hasIndex('rfq_items', 'rfq_items_rfq_id_index')) {
                    $table->index('rfq_id', 'rfq_items_rfq_id_index');
                }
                if (Schema::hasColumn('rfq_items', 'product_id') && !$this->hasIndex('rfq_items', 'rfq_items_product_id_index')) {
                    $table->index('product_id', 'rfq_items_product_id_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('RFQ items indexing failed: ' . $e->getMessage());
        }

        // Users table
        try {
            Schema::table('users', function (Blueprint $table) {
                if (Schema::hasColumn('users', 'email') && !$this->hasIndex('users', 'users_email_index')) {
                    $table->index('email', 'users_email_index');
                }
                if (Schema::hasColumn('users', 'role') && !$this->hasIndex('users', 'users_role_index')) {
                    $table->index('role', 'users_role_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Users indexing failed: ' . $e->getMessage());
        }

        // Cache table - improve cache operations
        try {
            Schema::table('cache', function (Blueprint $table) {
                if (!$this->hasIndex('cache', 'cache_expiration_index')) {
                    $table->index('expiration', 'cache_expiration_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Cache indexing failed: ' . $e->getMessage());
        }

        // Jobs table - improve queue processing
        try {
            Schema::table('jobs', function (Blueprint $table) {
                if (!$this->hasIndex('jobs', 'jobs_queue_reserved_at_index')) {
                    $table->index(['queue', 'reserved_at'], 'jobs_queue_reserved_at_index');
                }
            });
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::warning('Jobs indexing failed: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop all indexes created in up()
        $indexes = [
            'products' => [
                'products_title_index',
                'products_description_index',
                'products_price_index',
                'products_stock_index',
                'products_sector_index',
                'products_category_subcategory_index',
                'products_category_sector_index',
                'products_search_index',
            ],
            'posts' => [
                'posts_category_index',
                'posts_slug_index',
                'posts_status_index',
                'posts_created_at_index',
                'posts_category_status_index',
            ],
            'sectors' => [
                'sectors_name_index',
                'sectors_slug_index',
            ],
            'rfqs' => [
                'rfqs_user_id_index',
                'rfqs_status_index',
                'rfqs_created_at_index',
                'rfqs_user_status_index',
            ],
            'rfq_items' => [
                'rfq_items_rfq_id_index',
                'rfq_items_product_id_index',
            ],
            'users' => [
                'users_email_index',
                'users_role_index',
            ],
            'cache' => [
                'cache_expiration_index',
            ],
            'jobs' => [
                'jobs_queue_reserved_at_index',
            ],
        ];

        foreach ($indexes as $table => $indexList) {
            foreach ($indexList as $index) {
                try {
                    Schema::table($table, function (Blueprint $table) use ($index) {
                        $table->dropIndex($index);
                    });
                } catch (\Exception $e) {
                    // Index might not exist, skip
                }
            }
        }
    }

    /**
     * Check if an index exists on a table.
     */
    protected function hasIndex(string $table, string $indexName): bool
    {
        try {
            $connection = Schema::getConnection();
            $doctrineSchemaManager = $connection->getDoctrineSchemaManager();
            $doctrineTable = $doctrineSchemaManager->introspectTable($table);
            
            return $doctrineTable->hasIndex($indexName);
        } catch (\Exception $e) {
            // If we can't check, assume it doesn't exist and try to create
            return false;
        }
    }
};
