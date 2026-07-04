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
        DB::statement("CREATE OR REPLACE VIEW vw_books_with_authors AS
            SELECT a.name AS author_name, COUNT(b.id) AS book_count
            FROM authors a
            INNER JOIN book_author ba ON a.id = ba.author_id
            INNER JOIN books b ON b.id = ba.book_id
            GROUP BY a.id
            ORDER BY book_count DESC
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vw_books_with_authors_view');
    }
};
