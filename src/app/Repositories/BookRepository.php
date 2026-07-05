<?php

namespace App\Repositories;

use App\Models\Book;
use App\Models\VwBooksReport;

class BookRepository
{
    public function getPaginated(int $perPage = 25, string $sortBy = 'book_title', string $sortType = 'asc')
    {
        return VwBooksReport::orderBy($sortBy, $sortType)->paginate($perPage);
    }

    public function create(array $data): Book
    {
        return Book::create($data);
    }

    public function update(Book $book, array $data): Book
    {
        $book->update($data);
        return $book;
    }

    public function delete(Book $book): bool
    {
        return $book->delete();
    }
}
