<?php

use App\Models\Book;
use App\Models\VwBooksReport;
use App\Repositories\BookRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new BookRepository();
});

it('can get paginated books from report view', function () {
    // Para testar a view, normalmente criamos os dados na base real,
    // mas depende do suporte a views do BD em memória.
    // Assumimos que o factory funciona ou criamos diretamente no model:
    $book = Book::factory()->create(['title' => 'O Senhor dos Anéis']);

    // Se a view depender de tabelas auxiliares preenchidas (autores/assuntos),
    // VwBooksReport pode não trazer se houver JOINs INNER.
    // Por ser um teste básico, validamos o comportamento esperado da repository.
    $paginated = $this->repository->getPaginated(10, 'book_title', 'asc');

    expect($paginated)->toBeInstanceOf(\Illuminate\Pagination\LengthAwarePaginator::class);
    expect($paginated->total())->toBe(1);
    expect($paginated->items()[0]->book_title)->toBe('O Senhor dos Anéis');
});

it('can create a book', function () {
    $data = [
        'title' => 'O Hobbit',
        'publisher' => 'Martins Fontes',
        'edition' => 1,
        'publication_year' => '2000',
        'price' => 50.00
    ];

    $book = $this->repository->create($data);

    expect($book)->toBeInstanceOf(Book::class);
    expect($book->title)->toBe('O Hobbit');
    $this->assertDatabaseHas('books', ['title' => 'O Hobbit']);
});

it('can update a book', function () {
    $book = Book::factory()->create(['title' => 'O Hobbit 1']);
    $updated = $this->repository->update($book, ['title' => 'O Hobbit 2']);

    expect($updated->title)->toBe('O Hobbit 2');
    $this->assertDatabaseHas('books', ['id' => $book->id, 'title' => 'O Hobbit 2']);
});

it('can delete a book', function () {
    $book = Book::factory()->create();
    $result = $this->repository->delete($book);

    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('books', ['id' => $book->id]);
});
