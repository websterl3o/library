<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\BookController;
use App\Http\Controllers\Api\AuthorController;
use App\Http\Controllers\Api\SubjectController;

use App\Http\Controllers\Api\DashboardController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::apiResource('books', BookController::class);
Route::apiResource('authors', AuthorController::class);
Route::apiResource('subjects', SubjectController::class);

Route::prefix('dashboard')->group(function () {
    Route::get('/books-count', [DashboardController::class, 'countBooks']);
    Route::get('/authors-count', [DashboardController::class, 'countAuthors']);
    Route::get('/subjects-count', [DashboardController::class, 'countSubjects']);
    Route::get('/books-by-author', [DashboardController::class, 'booksByAuthor']);
    Route::get('/books-by-subject', [DashboardController::class, 'booksBySubject']);
});

Route::prefix('reports')->group(function () {
    Route::get('/authors', [\App\Http\Controllers\Api\ReportController::class, 'downloadAuthorsReport']);
    Route::get('/authors/{author}', [\App\Http\Controllers\Api\ReportController::class, 'downloadAuthorReport']);
});
