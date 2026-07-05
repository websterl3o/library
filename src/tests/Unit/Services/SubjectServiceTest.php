<?php

use App\Models\Subject;
use App\Repositories\SubjectRepository;
use App\Services\SubjectService;

it('can get paginated subjects', function () {
    $repositoryMock = Mockery::mock(SubjectRepository::class);
    $repositoryMock->shouldReceive('getPaginated')
        ->once()
        ->with(10, 'description', 'asc')
        ->andReturn('paginated_data');

    $service = new SubjectService($repositoryMock);
    $result = $service->getPaginated(10, 'description', 'asc');

    expect($result)->toBe('paginated_data');
});

it('can create a subject', function () {
    $data = ['description' => 'Ficção Científica'];
    $subject = new Subject($data);
    
    $repositoryMock = Mockery::mock(SubjectRepository::class);
    $repositoryMock->shouldReceive('create')
        ->once()
        ->with($data)
        ->andReturn($subject);

    $service = new SubjectService($repositoryMock);
    $result = $service->createSubject($data);

    expect($result)->toBe($subject);
});

it('can update a subject', function () {
    $subject = new Subject(['description' => 'Ficção']);
    $data = ['description' => 'Ficção Científica'];
    
    $repositoryMock = Mockery::mock(SubjectRepository::class);
    $repositoryMock->shouldReceive('update')
        ->once()
        ->with($subject, $data)
        ->andReturn($subject);

    $service = new SubjectService($repositoryMock);
    $result = $service->updateSubject($subject, $data);

    expect($result)->toBe($subject);
});
