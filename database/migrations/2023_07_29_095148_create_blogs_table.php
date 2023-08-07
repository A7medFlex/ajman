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
        Schema::create('blogs', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            $table->string('title_ar');
            $table->string('title_en')->nullable();

            $table->text('body_ar');
            $table->text('body_en')->nullable();

            $table->text('thumbnail')->nullable();

            $table->timestamps();

            $table->index(['id', 'user_id', 'title_ar', 'title_en']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs');
    }
};
