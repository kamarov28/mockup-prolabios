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
        if (! Schema::hasTable('newsletter_subscribers')) {
            Schema::create('newsletter_subscribers', function (Blueprint $table) {
                $table->id();
                $table->string('email')->unique();
                $table->string('first_name', 100);
                $table->string('last_name', 100)->nullable();
                $table->string('company', 255);
                $table->string('city', 150)->nullable();
                $table->string('country', 100)->default('Indonesia');
                $table->string('password'); // Hashed password for subscriber login / preference management
                $table->boolean('is_active')->default(true);
                $table->timestamp('email_verified_at')->nullable();
                $table->string('unsubscribe_token', 64)->unique();
                $table->rememberToken();
                $table->timestamps();
            });
        }

        if (! Schema::hasTable('newsletter_subscriber_sector')) {
            Schema::create('newsletter_subscriber_sector', function (Blueprint $table) {
                $table->id();
                $table->foreignId('subscriber_id')->constrained('newsletter_subscribers')->cascadeOnDelete();
                $table->string('sector_id');
                $table->foreign('sector_id')->references('id')->on('sectors')->cascadeOnDelete();
                $table->timestamps();

                $table->unique(['subscriber_id', 'sector_id'], 'subscriber_sector_unique');
            });
        }

        if (! Schema::hasTable('newsletter_broadcasts')) {
            Schema::create('newsletter_broadcasts', function (Blueprint $table) {
                $table->id();
                $table->string('subject');
                $table->string('target_sector_id')->nullable(); // null = All Sectors (General)
                $table->foreign('target_sector_id')->references('id')->on('sectors')->nullOnDelete();
                $table->string('type', 50)->default('announcement'); // product_release, regulatory_update, event, announcement
                $table->text('content');
                $table->integer('recipients_count')->default(0);
                $table->foreignId('sent_by_user_id')->nullable()->constrained('users')->nullOnDelete();
                $table->timestamp('sent_at')->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newsletter_broadcasts');
        Schema::dropIfExists('newsletter_subscriber_sector');
        Schema::dropIfExists('newsletter_subscribers');
    }
};
