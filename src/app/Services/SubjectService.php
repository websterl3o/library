<?php

namespace App\Services;

use App\Repositories\SubjectRepository;
use App\Models\Subject;
use App\Models\Book;

class SubjectService
{
    protected $subjectRepository;

    public function __construct(SubjectRepository $subjectRepository)
    {
        $this->subjectRepository = $subjectRepository;
    }

    public function getPaginated(int $perPage, string $sortBy, string $sortType)
    {
        return $this->subjectRepository->getPaginated($perPage, $sortBy, $sortType);
    }

    public function createSubject(array $data)
    {
        return $this->subjectRepository->create($data);
    }

    public function updateSubject(Subject $subject, array $data)
    {
        return $this->subjectRepository->update($subject, $data);
    }

    public function deleteSubject(Subject $subject)
    {
        // Regra de negócio: remover os livros associados antes de deletar o assunto
        $bookIds = $subject->books()->pluck('books.id');
        Book::whereIn('id', $bookIds)->delete();
        
        return $this->subjectRepository->delete($subject);
    }
}
