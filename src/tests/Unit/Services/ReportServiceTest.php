<?php

use App\Repositories\ReportRepository;
use App\Services\ReportService;
use Illuminate\Database\Eloquent\Collection;

it('formats all authors report data correctly', function () {
    $mockData = new Collection([
        (object) [
            'author_id' => 1,
            'author_name' => 'Machado de Assis',
            'book_id' => 10,
            'book_title' => 'Dom Casmurro',
            'book_publisher' => 'Editora A',
            'book_year' => '1899',
            'concatenated_subjects' => 'Romance, Clássico'
        ],
        (object) [
            'author_id' => 1,
            'author_name' => 'Machado de Assis',
            'book_id' => 11,
            'book_title' => 'Memórias Póstumas',
            'book_publisher' => 'Editora B',
            'book_year' => '1881',
            'concatenated_subjects' => 'Romance'
        ]
    ]);

    $repositoryMock = Mockery::mock(ReportRepository::class);
    $repositoryMock->shouldReceive('getAllAuthorsReport')
        ->once()
        ->andReturn($mockData);

    $service = new ReportService($repositoryMock);
    $result = $service->getAllAuthorsReportData();

    expect($result)->toHaveKey(1);
    expect($result[1]['nome'])->toBe('Machado de Assis');
    expect($result[1]['livros'])->toHaveCount(2);
    expect($result[1]['livros'][0]['titulo'])->toBe('Dom Casmurro');
});

it('formats specific author report data correctly', function () {
    $mockData = new Collection([
        (object) [
            'author_id' => 2,
            'author_name' => 'Clarice Lispector',
            'book_id' => 20,
            'book_title' => 'A Hora da Estrela',
            'book_publisher' => 'Editora C',
            'book_year' => '1977',
            'concatenated_subjects' => 'Ficção'
        ]
    ]);

    $repositoryMock = Mockery::mock(ReportRepository::class);
    $repositoryMock->shouldReceive('getAuthorReport')
        ->once()
        ->with(2)
        ->andReturn($mockData);

    $service = new ReportService($repositoryMock);
    $result = $service->getAuthorReportData(2);

    expect($result)->not->toBeNull();
    expect($result)->toHaveKey(2);
    expect($result[2]['livros'])->toHaveCount(1);
});

it('returns null if specific author has no records', function () {
    $repositoryMock = Mockery::mock(ReportRepository::class);
    $repositoryMock->shouldReceive('getAuthorReport')
        ->once()
        ->with(99)
        ->andReturn(new Collection());

    $service = new ReportService($repositoryMock);
    $result = $service->getAuthorReportData(99);

    expect($result)->toBeNull();
});
