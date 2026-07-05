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
        DB::statement("
            CREATE OR REPLACE VIEW vw_author_books_report AS
            SELECT 
                a.id AS author_id, 
                a.name AS author_name,
                b.id AS book_id, 
                b.title AS book_title, 
                b.publisher AS book_publisher, 
                b.publication_year AS book_year,
                GROUP_CONCAT(s.description SEPARATOR ', ') AS concatenated_subjects
            FROM authors a
            LEFT JOIN book_author ba ON a.id = ba.author_id
            LEFT JOIN books b ON ba.book_id = b.id
            LEFT JOIN book_subject bs ON b.id = bs.book_id
            LEFT JOIN subjects s ON bs.subject_id = s.id
            GROUP BY a.id, a.name, b.id, b.title, b.publisher, b.publication_year
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::statement("DROP VIEW IF EXISTS vw_author_books_report");
    }
};
