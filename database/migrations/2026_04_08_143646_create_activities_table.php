<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();  // Changed from chapter_id
            $table->string('title');
            $table->text('objective')->nullable();
            $table->text('materials_needed')->nullable();  // Changed from materials
            $table->text('instructions')->nullable();
            $table->integer('estimated_duration')->nullable();
            $table->string('difficulty_level')->nullable();  // Added
            $table->boolean('is_published')->default(false);  // Added
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();  // Added
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activities');
    }
};
