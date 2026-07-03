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
        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW vw_book_report AS
            SELECT 
                b.id AS book_id,
                b.title AS book_title,
                b.publisher AS book_publisher,
                b.edition AS book_edition,
                b.publication_year AS book_year,
                b.price AS book_price,
                GROUP_CONCAT(DISTINCT a.name SEPARATOR ', ') AS authors_list,
                GROUP_CONCAT(DISTINCT s.description SEPARATOR ', ') AS subjects_list
            FROM books b
            LEFT JOIN book_author ba ON b.id = ba.book_id
            LEFT JOIN authors a ON ba.author_id = a.id
            LEFT JOIN book_subject bs ON b.id = bs.book_id
            LEFT JOIN subjects s ON bs.subject_id = s.id
            GROUP BY b.id, b.title, b.publisher, b.edition, b.publication_year, b.price
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert to old view
        \Illuminate\Support\Facades\DB::statement("
            CREATE OR REPLACE VIEW vw_book_report AS
            SELECT 
                a.id AS author_id,
                a.name AS author_name,
                b.id AS book_id,
                b.title AS book_title,
                b.publisher AS book_publisher,
                b.edition AS book_edition,
                b.publication_year AS book_year,
                b.price AS book_price,
                GROUP_CONCAT(DISTINCT s.description SEPARATOR ', ') AS subjects_list
            FROM authors a
            JOIN book_author ba ON a.id = ba.author_id
            JOIN books b ON ba.book_id = b.id
            LEFT JOIN book_subject bs ON b.id = bs.book_id
            LEFT JOIN subjects s ON bs.subject_id = s.id
            GROUP BY a.id, a.name, b.id, b.title, b.publisher, b.edition, b.publication_year, b.price
        ");
    }
};
