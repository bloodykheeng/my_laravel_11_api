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
        Schema::create('product_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('status')->default('active')->index();
            $table->text('description')->nullable();
            $table->string('photo_url')->nullable();
            $table->timestamps();

            // Assuming you want to track who created and updated the product type
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();

            // Foreign key constraints
            $table->foreign('created_by')->references('id')->on('users')->onDelete('SET NULL');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('SET NULL');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_types');
    }
};