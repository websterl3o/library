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
        \Illuminate\Support\Facades\DB::statement("CREATE OR REPLACE VIEW vw_books_with_subjects AS
            SELECT s.description AS subject, COUNT(b.id) AS book_count
            FROM subjects s
            INNER JOIN book_subject bs ON s.id = bs.subject_id
            INNER JOIN books b ON b.id = bs.book_id
            GROUP BY s.id
            ORDER BY book_count DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        \Illuminate\Support\Facades\DB::statement("DROP VIEW IF EXISTS vw_books_with_subjects");
    }
};
