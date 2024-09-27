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
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('model_for_permission_id')->constrained()->cascadeOnDelete();
            $table->string('permission_name');
            $table->boolean('is_viewer')->default(false);
            $table->boolean('is_creator')->default(false);
            $table->boolean('is_updater')->default(false);
            $table->boolean('is_eraser')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('permissions');
    }
};
