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
        Schema::create('tagables', function (Blueprint $table) {
            $table->primary(['tag_id', 'tagable_id', 'tagable_type']);
            $table->foreignId('tag_id')->constrained()->cascadeOnDelete();
            $table->morphs('tagable');

            $table->index(['tag_id', 'tagable_id', 'tagable_type']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagables');
    }
};
