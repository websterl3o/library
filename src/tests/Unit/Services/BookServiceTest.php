<?php

use App\Models\Book;
use App\Repositories\BookRepository;
use App\Services\BookService;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

it('can get paginated books', function () {
    $repositoryMock = Mockery::mock(BookRepository::class);
    $repositoryMock->shouldReceive('getPaginated')
        ->once()
        ->with(10, 'book_title', 'asc')
        ->andReturn('paginated_data');

    $service = new BookService($repositoryMock);
    $result = $service->getPaginated(10, 'book_title', 'asc');

    expect($result)->toBe('paginated_data');
});

it('can delete a book', function () {
    $book = new Book(['title' => 'Livro Teste']);
    
    $repositoryMock = Mockery::mock(BookRepository::class);
    $repositoryMock->shouldReceive('delete')
        ->once()
        ->with($book)
        ->andReturn(true);

    $service = new BookService($repositoryMock);
    $result = $service->deleteBook($book);

    expect($result)->toBeTrue();
});

it('can create a book and sync relations', function () {
    $bookMock = Mockery::mock(Book::class)->makePartial();
    $authorsRelationMock = Mockery::mock(BelongsToMany::class);
    $subjectsRelationMock = Mockery::mock(BelongsToMany::class);
    
    $bookMock->shouldReceive('authors')->once()->andReturn($authorsRelationMock);
    $authorsRelationMock->shouldReceive('sync')->once()->with([1, 2]);
    
    $bookMock->shouldReceive('subjects')->once()->andReturn($subjectsRelationMock);
    $subjectsRelationMock->shouldReceive('sync')->once()->with([3]);

    $repositoryMock = Mockery::mock(BookRepository::class);
    $repositoryMock->shouldReceive('create')
        ->once()
        ->with(['title' => 'Novo Livro'])
        ->andReturn($bookMock);

    $service = new BookService($repositoryMock);
    $result = $service->createBook(['title' => 'Novo Livro'], [1, 2], [3]);

    expect($result)->toBe($bookMock);
});

it('can update a book and sync relations when provided', function () {
    $bookMock = Mockery::mock(Book::class)->makePartial();
    $authorsRelationMock = Mockery::mock(BelongsToMany::class);
    $subjectsRelationMock = Mockery::mock(BelongsToMany::class);
    
    $bookMock->shouldReceive('authors')->once()->andReturn($authorsRelationMock);
    $authorsRelationMock->shouldReceive('sync')->once()->with([1, 2]);
    
    $bookMock->shouldReceive('subjects')->once()->andReturn($subjectsRelationMock);
    $subjectsRelationMock->shouldReceive('sync')->once()->with([3]);

    $repositoryMock = Mockery::mock(BookRepository::class);
    $repositoryMock->shouldReceive('update')
        ->once()
        ->with($bookMock, ['title' => 'Livro Atualizado'])
        ->andReturn($bookMock);

    $service = new BookService($repositoryMock);
    $result = $service->updateBook($bookMock, ['title' => 'Livro Atualizado'], [1, 2], [3]);

    expect($result)->toBe($bookMock);
});

it('can update a book without syncing relations when null is provided', function () {
    $bookMock = Mockery::mock(Book::class)->makePartial();
    
    // As assertions here are that 'authors' and 'subjects' methods are NEVER called.
    $bookMock->shouldReceive('authors')->never();
    $bookMock->shouldReceive('subjects')->never();

    $repositoryMock = Mockery::mock(BookRepository::class);
    $repositoryMock->shouldReceive('update')
        ->once()
        ->with($bookMock, ['title' => 'Livro Atualizado Sem Relacoes'])
        ->andReturn($bookMock);

    $service = new BookService($repositoryMock);
    // Passing null explicitly for authors and subjects
    $result = $service->updateBook($bookMock, ['title' => 'Livro Atualizado Sem Relacoes'], null, null);

    expect($result)->toBe($bookMock);
});
