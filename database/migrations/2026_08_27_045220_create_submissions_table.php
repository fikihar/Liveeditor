<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('assignment_id')->constrained('assignments')->cascadeOnDelete();
            $table->foreignId('student_id')->constrained('users')->cascadeOnDelete();
            $table->longText('html_code')->nullable();
            $table->longText('css_code')->nullable();
            $table->enum('status', ['draft', 'submitted'])->default('draft');
            $table->integer('score')->nullable(); // nilai hasil auto-grading
            $table->json('grading_detail')->nullable(); // detail per kriteria
            $table->timestamp('submitted_at')->nullable();
            $table->timestamps();

            // Satu siswa hanya punya satu submission per assignment
            $table->unique(['assignment_id', 'student_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('submissions');
    }
};
