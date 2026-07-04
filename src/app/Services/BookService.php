<?php

namespace App\Services;

use App\Repositories\BookRepository;
use App\Models\Book;

class BookService
{
    protected $bookRepository;

    public function __construct(BookRepository $bookRepository)
    {
        $this->bookRepository = $bookRepository;
    }

    public function getPaginated(int $perPage, string $sortBy, string $sortType)
    {
        return $this->bookRepository->getPaginated($perPage, $sortBy, $sortType);
    }

    public function createBook(array $data, array $authors = [], array $subjects = [])
    {
        $book = $this->bookRepository->create($data);
        
        $book->authors()->sync($authors);
        $book->subjects()->sync($subjects);

        return $book;
    }

    public function updateBook(Book $book, array $data, ?array $authors = null, ?array $subjects = null)
    {
        $book = $this->bookRepository->update($book, $data);

        if ($authors !== null) {
            $book->authors()->sync($authors);
        }
        if ($subjects !== null) {
            $book->subjects()->sync($subjects);
        }

        return $book;
    }

    public function deleteBook(Book $book)
    {
        return $this->bookRepository->delete($book);
    }
}
