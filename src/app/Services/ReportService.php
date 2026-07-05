<?php

namespace App\Services;

use App\Repositories\ReportRepository;
use Illuminate\Database\Eloquent\Collection;

class ReportService
{
    protected ReportRepository $repository;

    public function __construct(ReportRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Get structured data for all authors
     * 
     * @return array
     */
    public function getAllAuthorsReportData(): array
    {
        $data = $this->repository->getAllAuthorsReport();
        return $this->formatReportData($data);
    }

    /**
     * Get structured data for a specific author
     * 
     * @param int $authorId
     * @return array|null Null if author not found or has no records
     */
    public function getAuthorReportData(int $authorId): ?array
    {
        $data = $this->repository->getAuthorReport($authorId);
        
        if ($data->isEmpty()) {
            return null;
        }

        return $this->formatReportData($data);
    }

    /**
     * Format collection data into nested array structure
     * 
     * @param Collection $data
     * @return array
     */
    private function formatReportData(Collection $data): array
    {
        $authors = [];
        
        foreach ($data as $row) {
            $authorId = $row->author_id;
            
            if (!isset($authors[$authorId])) {
                $authors[$authorId] = [
                    'id' => $authorId,
                    'nome' => $row->author_name,
                    'livros' => []
                ];
            }
            
            if ($row->book_id) {
                $authors[$authorId]['livros'][] = [
                    'id' => $row->book_id,
                    'titulo' => $row->book_title,
                    'editora' => $row->book_publisher,
                    'ano_publicacao' => $row->book_year,
                    'assuntos_concatenados' => $row->concatenated_subjects
                ];
            }
        }
        
        return $authors;
    }
}
