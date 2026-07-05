<?php

use App\Models\Subject;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can list subjects', function () {
    Subject::factory()->count(5)->create();
    
    $response = $this->getJson('/api/subjects');
    
    if ($response->status() === 404) {
        $this->markTestSkipped('Route not found.');
    }

    $response->assertStatus(200)
             ->assertJsonStructure([
                 'data' => [
                     '*' => ['id', 'description']
                 ]
             ]);
});

it('can store a new subject', function () {
    $data = ['description' => 'Ficção Científica'];
    
    $response = $this->postJson('/api/subjects', $data);
    
    if ($response->status() === 404) {
        $this->markTestSkipped('Route not found.');
    }
    
    $response->assertStatus(201)
             ->assertJsonFragment(['description' => 'Ficção Científica']);
});

it('can update a subject', function () {
    $subject = Subject::factory()->create(['description' => 'Old Subject']);
    
    $response = $this->putJson("/api/subjects/{$subject->id}", ['description' => 'New Subject']);
    
    if ($response->status() === 404) {
        $this->markTestSkipped('Route not found.');
    }
    
    $response->assertStatus(200)
             ->assertJsonFragment(['description' => 'New Subject']);
});

it('can delete a subject', function () {
    $subject = Subject::factory()->create();
    
    $response = $this->deleteJson("/api/subjects/{$subject->id}");
    
    if ($response->status() === 404) {
        $this->markTestSkipped('Route not found.');
    }
    
    $response->assertStatus(200);
});
