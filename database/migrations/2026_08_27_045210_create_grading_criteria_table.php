<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('grading_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            // Tipe pengecekan: has_tag (ada elemen HTML), has_css (ada properti CSS),
            // has_attribute (ada atribut), has_text (ada teks tertentu)
            $table->enum('type', ['has_tag', 'has_css', 'has_attribute', 'has_text']);
            $table->string('target'); // contoh: "h1", "color", "border", "src"
            $table->string('value')->nullable(); // nilai spesifik (opsional)
            $table->string('description'); // keterangan untuk guru: "Harus ada tag <h1>"
            $table->integer('points')->default(10); // bobot nilai
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('grading_criteria');
    }
};
