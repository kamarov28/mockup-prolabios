<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_categories', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique()->comment('Slug unik, contoh: microbiology, food-safety');
            $table->string('name')->comment('Nama tampil, contoh: Microbiology');
            $table->unsignedBigInteger('parent_id')->nullable()->comment('NULL = kategori utama, isi = sub-kategori');
            $table->integer('sort_order')->default(0)->comment('Urutan tampil di sidebar');
            $table->timestamps();

            $table->foreign('parent_id')
                ->references('id')
                ->on('product_categories')
                ->onDelete('cascade');

            $table->index('parent_id');
            $table->index(['parent_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_categories');
    }
};
