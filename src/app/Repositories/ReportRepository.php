<?php

namespace App\Repositories;

use App\Models\VwAuthorBooksReport;
use Illuminate\Database\Eloquent\Collection;

class ReportRepository
{
    /**
     * Get all authors and their books
     * 
     * @return Collection
     */
    public function getAllAuthorsReport(): Collection
    {
        return VwAuthorBooksReport::all();
    }

    /**
     * Get report data for a specific author
     * 
     * @param int $authorId
     * @return Collection
     */
    public function getAuthorReport(int $authorId): Collection
    {
        return VwAuthorBooksReport::where('author_id', $authorId)->get();
    }
}
