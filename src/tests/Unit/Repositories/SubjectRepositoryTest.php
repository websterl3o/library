<?php

use App\Models\Subject;
use App\Repositories\SubjectRepository;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

beforeEach(function () {
    $this->repository = new SubjectRepository();
});

it('can get paginated subjects', function () {
    Subject::factory()->count(5)->create();
    $paginated = $this->repository->getPaginated(2, 'description', 'asc');
    
    expect($paginated)->toHaveCount(2);
    expect($paginated->total())->toBe(5);
});

it('can create a subject', function () {
    $subject = $this->repository->create(['description' => 'Ficção Científica']);
    
    expect($subject)->toBeInstanceOf(Subject::class);
    expect($subject->description)->toBe('Ficção Científica');
    $this->assertDatabaseHas('subjects', ['description' => 'Ficção Científica']);
});

it('can update a subject', function () {
    $subject = Subject::factory()->create(['description' => 'Ficção']);
    $updated = $this->repository->update($subject, ['description' => 'Ficção Científica']);
    
    expect($updated->description)->toBe('Ficção Científica');
    $this->assertDatabaseHas('subjects', ['id' => $subject->id, 'description' => 'Ficção Científica']);
});

it('can delete a subject', function () {
    $subject = Subject::factory()->create();
    $result = $this->repository->delete($subject);
    
    expect($result)->toBeTrue();
    $this->assertDatabaseMissing('subjects', ['id' => $subject->id]);
});
