<?php

namespace App\Services;

use App\Repositories\AuthorRepository;
use App\Models\Author;
use App\Models\Book;

class AuthorService
{
    protected $authorRepository;

    public function __construct(AuthorRepository $authorRepository)
    {
        $this->authorRepository = $authorRepository;
    }

    public function getPaginated(int $perPage, string $sortBy, string $sortType)
    {
        return $this->authorRepository->getPaginated($perPage, $sortBy, $sortType);
    }

    public function createAuthor(array $data)
    {
        return $this->authorRepository->create($data);
    }

    public function updateAuthor(Author $author, array $data)
    {
        return $this->authorRepository->update($author, $data);
    }

    public function deleteAuthor(Author $author)
    {
        // Regra de negócio: remover os livros associados antes de deletar o autor
        $bookIds = $author->books()->pluck('books.id');
        Book::whereIn('id', $bookIds)->delete();
        
        return $this->authorRepository->delete($author);
    }
}
