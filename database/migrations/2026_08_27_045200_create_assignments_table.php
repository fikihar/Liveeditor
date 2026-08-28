<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained('classes')->cascadeOnDelete();
            $table->string('title');
            $table->text('description')->nullable();
            $table->enum('type', ['latihan', 'tugas'])->default('latihan');
            $table->dateTime('deadline')->nullable(); // hanya untuk tugas
            $table->longText('starter_html')->nullable(); // kode HTML awal (opsional)
            $table->longText('starter_css')->nullable();  // kode CSS awal (opsional)
            $table->integer('max_score')->default(100);
            $table->boolean('is_graded')->default(false); // apakah ada auto-grading
            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
