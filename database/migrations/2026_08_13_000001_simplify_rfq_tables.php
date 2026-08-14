<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {
            if (! Schema::hasColumn('rfqs', 'name')) {
                $table->string('name')->after('rfq_number')->default('');
            }
        });

        // Copy pic_name to name for existing records if pic_name exists
        if (Schema::hasColumn('rfqs', 'pic_name')) {
            DB::table('rfqs')->where('name', '')->orWhereNull('name')->update([
                'name' => DB::raw('pic_name'),
            ]);
        }

        $indexesToDrop = ['rfqs_status_index', 'rfqs_user_status_index', 'rfqs_access_token_index'];
        foreach ($indexesToDrop as $indexName) {
            try {
                $driver = Schema::getConnection()->getDriverName();
                if ($driver === 'sqlite') {
                    DB::statement("DROP INDEX IF EXISTS {$indexName}");
                } else {
                    Schema::table('rfqs', function (Blueprint $table) use ($indexName) {
                        $table->dropIndex($indexName);
                    });
                }
            } catch (Throwable $e) {
                // Ignore if not found
            }
        }

        Schema::table('rfqs', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['company_tax_id', 'pic_name', 'pic_position', 'address', 'status', 'access_token', 'total_offered_amount', 'admin_response_notes', 'valid_until'] as $col) {
                if (Schema::hasColumn('rfqs', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });

        Schema::table('rfq_items', function (Blueprint $table) {
            $columnsToDrop = [];
            foreach (['offered_price', 'subtotal'] as $col) {
                if (Schema::hasColumn('rfq_items', $col)) {
                    $columnsToDrop[] = $col;
                }
            }
            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rfqs', function (Blueprint $table) {
            $table->string('company_tax_id')->nullable();
            $table->string('pic_name')->nullable();
            $table->string('pic_position')->nullable();
            $table->text('address')->nullable();
            $table->string('status')->default('pending_review');
            $table->string('access_token', 64)->nullable();
            $table->decimal('total_offered_amount', 15, 2)->default(0);
            $table->text('admin_response_notes')->nullable();
            $table->date('valid_until')->nullable();
        });

        Schema::table('rfq_items', function (Blueprint $table) {
            $table->decimal('offered_price', 15, 2)->default(0);
            $table->decimal('subtotal', 15, 2)->default(0);
        });

        Schema::table('rfqs', function (Blueprint $table) {
            if (Schema::hasColumn('rfqs', 'name')) {
                $table->dropColumn('name');
            }
        });
    }
};
