<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('datasets', function (Blueprint $table) {
            $table->id();
            $table->integer('id_user');
            $table->integer('id_subject_area')->nullable();
            $table->string('name');
            $table->text('abstract')->nullable();
            $table->integer('instances')->nullable();
            $table->integer('features')->nullable();
            $table->text('information')->nullable();
            $table->enum('status', ['pending', 'valid', 'invalid'])->default('pending');
            $table->string('note')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('datasets');
    }
};
