<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Dashboard');
});

// Books
Route::get('/books', function () {
    return Inertia::render('Books/Index');
});
Route::get('/books/create', function () {
    return Inertia::render('Books/Create');
});

// Authors
Route::get('/authors', function () {
    return Inertia::render('Authors/Index');
});
Route::get('/authors/create', function () {
    return Inertia::render('Authors/Create');
});

// Subjects
Route::get('/subjects', function () {
    return Inertia::render('Subjects/Index');
});
Route::get('/subjects/create', function () {
    return Inertia::render('Subjects/Create');
});
