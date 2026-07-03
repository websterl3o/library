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
        Schema::create('book_subject', function (Blueprint $table) {
            $table->unsignedBigInteger('book_id');
            $table->unsignedBigInteger('subject_id');

            $table->primary(['book_id', 'subject_id']);

            $table->foreign('book_id')->references('id')->on('books')->onDelete('cascade');
            $table->foreign('subject_id')->references('id')->on('subjects')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_subject');
    }
};
