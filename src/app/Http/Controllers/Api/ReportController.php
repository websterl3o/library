<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\ReportService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    protected ReportService $service;

    public function __construct(ReportService $service)
    {
        $this->service = $service;
    }

    /**
     * Download the report for all authors
     */
    public function downloadAuthorsReport()
    {
        $authors = $this->service->getAllAuthorsReportData();
        
        $pdf = Pdf::loadView('reports.authors', ['autores' => $authors]);
        
        return $pdf->download('relatorio-autores.pdf');
    }

    /**
     * Download the report for a specific author
     */
    public function downloadAuthorReport($authorId)
    {
        $authors = $this->service->getAuthorReportData($authorId);
        
        if ($authors === null) {
            return response()->json(['message' => 'Autor não encontrado ou sem registros.'], 404);
        }
        
        $pdf = Pdf::loadView('reports.authors', ['autores' => $authors]);
        
        $authorName = preg_replace('/[^a-zA-Z0-9_-]/', '_', strtolower($authors[$authorId]['nome'] ?? 'autor'));
        
        return $pdf->download("relatorio-autor-{$authorName}.pdf");
    }
}
