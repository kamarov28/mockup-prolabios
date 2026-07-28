<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('rfqs', function (Blueprint $table) {
            $table->id();
            $table->string('rfq_number')->unique();
            $table->string('company_name');
            $table->string('company_tax_id')->nullable(); // NPWP / NIB
            $table->string('pic_name');
            $table->string('pic_position')->nullable();
            $table->string('email');
            $table->string('phone_wa');
            $table->text('address');
            $table->text('notes')->nullable();
            $table->string('status')->default('pending_review'); // pending_review, quotation_sent, approved, rejected, completed
            $table->decimal('total_offered_amount', 15, 2)->default(0);
            $table->text('admin_response_notes')->nullable();
            $table->date('valid_until')->nullable();
            $table->timestamps();
        });

        Schema::create('rfq_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('rfq_id')->constrained('rfqs')->onDelete('cascade');
            $table->unsignedBigInteger('product_id')->nullable();
            $table->string('product_title');
            $table->string('catalog_no')->nullable();
            $table->decimal('original_price', 15, 2)->default(0);
            $table->decimal('offered_price', 15, 2)->default(0);
            $table->integer('quantity')->default(1);
            $table->decimal('subtotal', 15, 2)->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rfq_items');
        Schema::dropIfExists('rfqs');
    }
};
