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
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->string('image_path'); // To store the image path
            $table->unsignedBigInteger('id_dep')->nullable(); // Foreign key for specialization (optional)
            $table->unsignedBigInteger('id_user')->nullable(); // Foreign key for specialization (optional)
            $table->unsignedBigInteger('id_sp')->nullable(); // Foreign key for specialization (optional)
            $table->unsignedBigInteger('id_event')->nullable(); // Foreign key for specialization (optional)

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('images');
    }
};
