<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\BookReport;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;

class BookController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Utilizando a View (BookReport) que consolida os subjects e resolve relacionamentos internamente no MySQL
        return response()->json(BookReport::paginate(10));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreBookRequest $request)
    {
        $book = Book::create($request->validated());
        
        $book->authors()->sync($request->authors);
        $book->subjects()->sync($request->subjects);

        return response()->json($book->load(['authors', 'subjects']), 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Book $book)
    {
        return response()->json($book->load(['authors', 'subjects']));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookRequest $request, Book $book)
    {
        $book->update($request->validated());

        if ($request->has('authors')) {
            $book->authors()->sync($request->authors);
        }
        if ($request->has('subjects')) {
            $book->subjects()->sync($request->subjects);
        }

        return response()->json($book->load(['authors', 'subjects']));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Book $book)
    {
        // Pivot tables cascade delete on DB side
        $book->delete();
        return response()->json(null, 204);
    }
}
