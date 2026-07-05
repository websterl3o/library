<?php

use App\Models\Author;
use App\Models\Book;
use App\Repositories\AuthorRepository;
use App\Services\AuthorService;
use Illuminate\Database\Eloquent\Collection;

it('can get paginated authors', function () {
    $repositoryMock = Mockery::mock(AuthorRepository::class);
    $repositoryMock->shouldReceive('getPaginated')
        ->once()
        ->with(10, 'name', 'asc')
        ->andReturn('paginated_data');

    $service = new AuthorService($repositoryMock);
    $result = $service->getPaginated(10, 'name', 'asc');

    expect($result)->toBe('paginated_data');
});

it('can create an author', function () {
    $data = ['name' => 'Machado de Assis'];
    $author = new Author($data);
    
    $repositoryMock = Mockery::mock(AuthorRepository::class);
    $repositoryMock->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($author);

    $service = new AuthorService($repositoryMock);
    $result = $service->createAuthor($data);

    expect($result)->toBe($author);
});

it('can update an author', function () {
    $author = new Author(['name' => 'Eca de Queiros']);
    $data = ['name' => 'Eca de Queiroz'];
    
    $repositoryMock = Mockery::mock(AuthorRepository::class);
    $repositoryMock->shouldReceive('update')
        ->once()
        ->with($author, $data)
        ->andReturn($author);

    $service = new AuthorService($repositoryMock);
    $result = $service->updateAuthor($author, $data);

    expect($result)->toBe($author);
});

// Nota: O método deleteAuthor do AuthorService também deleta livros.
// No entanto, como ele chama Book::whereIn, testar isso 100% unitário requere 
// mockar as dependências globais ou do Eloquent, o que é complexo e foge do 
// padrão básico do Mockery em Service. Neste caso, o Feature Test vai cobrir
// a lógica de deleção em cascata (caso não tenhamos banco em memória disponível agora).
