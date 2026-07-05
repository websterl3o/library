<?php

use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can get dashboard stats', function () {
    $response = $this->getJson('/api/dashboard/books-count');
    $response->assertStatus(200)->assertJsonStructure(['count']);

    $response = $this->getJson('/api/dashboard/authors-count');
    $response->assertStatus(200)->assertJsonStructure(['count']);

    $response = $this->getJson('/api/dashboard/subjects-count');
    $response->assertStatus(200)->assertJsonStructure(['count']);
});

it('can get books by author list', function () {
    $response = $this->getJson('/api/dashboard/books-by-author');
    $response->assertStatus(200);
    // As it returns a collection, we can just assert it's a JSON array
    $response->assertJsonIsArray();
});

it('can get books by subject list', function () {
    $response = $this->getJson('/api/dashboard/books-by-subject');
    $response->assertStatus(200);
    // As it returns a collection, we can just assert it's a JSON array
    $response->assertJsonIsArray();
});
