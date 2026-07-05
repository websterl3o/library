<?php

use App\Models\Author;
use App\Models\Book;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can download all authors report', function () {
    // Generate some basic data
    $author = Author::factory()->create();
    $book = Book::factory()->create();
    $book->authors()->sync([$author->id]);
    
    // As it uses a view, and views in testing DB (sqlite memory usually) can be tricky,
    // we assume MySQL is used here or the View exists. We can just test the route response status.
    $response = $this->get('/api/reports/authors');
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('can download single author report', function () {
    $author = Author::factory()->create();
    $book = Book::factory()->create();
    $book->authors()->sync([$author->id]);
    
    $response = $this->get("/api/reports/authors/{$author->id}");
    
    $response->assertStatus(200);
    $response->assertHeader('Content-Type', 'application/pdf');
});

it('returns 404 for a non-existent author report', function () {
    $response = $this->get('/api/reports/authors/99999');
    
    $response->assertStatus(404);
});
