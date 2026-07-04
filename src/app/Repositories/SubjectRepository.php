<?php

namespace App\Repositories;

use App\Models\Subject;

class SubjectRepository
{
    public function getPaginated(int $perPage = 25, string $sortBy = 'description', string $sortType = 'asc')
    {
        return Subject::orderBy($sortBy, $sortType)->paginate($perPage);
    }

    public function create(array $data): Subject
    {
        return Subject::create($data);
    }

    public function update(Subject $subject, array $data): Subject
    {
        $subject->update($data);
        return $subject;
    }

    public function delete(Subject $subject): bool
    {
        return $subject->delete();
    }
}
