<?php

namespace App\Repositories;

use App\Models\Author;

class AuthorRepository
{
    public function getPaginated(int $perPage = 25, string $sortBy = 'name', string $sortType = 'asc')
    {
        return Author::orderBy($sortBy, $sortType)->paginate($perPage);
    }

    public function create(array $data): Author
    {
        return Author::create($data);
    }

    public function update(Author $author, array $data): Author
    {
        $author->update($data);
        return $author;
    }

    public function delete(Author $author): bool
    {
        return $author->delete();
    }
}
