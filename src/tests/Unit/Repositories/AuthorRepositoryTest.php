<?php

use App\Models\Author;
use App\Repositories\AuthorRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new AuthorRepository();
});

it('can get paginated authors', function () {
    Author::factory()->count(5)->create();
    $paginated = $this->repository->getPaginated(2, 'name', 'asc');
    
    expect($paginated)->toHaveCount(2);
    expect($paginated->total())->toBe(5);
});

it('can create an author', function () {
    $author = $this->repository->create(['name' => 'Machado de Assis']);
    
    expect($author)->toBeInstanceOf(Author::class);
    expect($author->name)->toBe('Machado de Assis');
    $this->assertDatabaseHas('authors', ['name' => 'Machado de Assis']);
});

it('can update an author', function () {
    $author = Author::factory()->create(['name' => 'Eca de Queiros']);
    $updated = $this->repository->update($author, ['name' => 'Eca de Queiroz']);
    
    expect($updated->name)->toBe('Eca de Queiroz');
    $this->assertDatabaseHas('authors', ['id' => $author->id, 'name' => 'Eca de Queiroz']);
});

it('can delete an author', function () {
    $author = Author::factory()->create();
    $result = $this->repository->delete($author);
    
    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('authors', ['id' => $author->id]);
});
