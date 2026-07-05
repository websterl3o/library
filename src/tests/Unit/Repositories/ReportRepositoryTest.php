<?php

use App\Models\Author;
use App\Models\Book;
use App\Models\Subject;
use App\Repositories\ReportRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get all authors report', function () {
    $author = Author::factory()->create(['name' => 'Autor Teste']);
    $book = Book::factory()->create(['title' => 'Livro Teste']);
    $book->authors()->sync([$author->id]);
    
    $repository = new ReportRepository();
    $data = $repository->getAllAuthorsReport();
    
    expect($data)->not->toBeEmpty();
    expect($data->first()->author_name)->toBe('Autor Teste');
    expect($data->first()->book_title)->toBe('Livro Teste');
});

it('can get specific author report', function () {
    $author1 = Author::factory()->create(['name' => 'Autor Um']);
    $author2 = Author::factory()->create(['name' => 'Autor Dois']);
    
    $book1 = Book::factory()->create(['title' => 'Livro Um']);
    $book1->authors()->sync([$author1->id]);
    
    $repository = new ReportRepository();
    $data = $repository->getAuthorReport($author1->id);
    
    expect($data)->toHaveCount(1);
    expect($data->first()->author_name)->toBe('Autor Um');
});
