<?php

// database/migrations/xxxx_create_categories_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('education_level_id')->constrained('education_levels')->onDelete('cascade');
            $table->foreignId('subject_type_id')->constrained('subject_types')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['education_level_id', 'subject_type_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};

