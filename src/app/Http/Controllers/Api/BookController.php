<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Services\BookService;
use App\Http\Requests\StoreBookRequest;
use App\Http\Requests\UpdateBookRequest;
use Illuminate\Http\Request;

class BookController extends Controller
{
    protected $bookService;

    public function __construct(BookService $bookService)
    {
        $this->bookService = $bookService;
    }

    public function index()
    {
        $perPage = request('per_page', 25);
        $sortBy = request('sortBy', 'book_title');
        $sortType = request('sortType', 'asc');

        $books = $this->bookService->getPaginated($perPage, $sortBy, $sortType);
        return response()->json($books);
    }

    public function store(StoreBookRequest $request)
    {
        $book = $this->bookService->createBook(
            $request->validated(),
            $request->authors ?? [],
            $request->subjects ?? []
        );

        return response()->json($book->load(['authors', 'subjects']), 201);
    }

    public function show(Book $book)
    {
        return response()->json($book->load(['authors', 'subjects']));
    }

    public function update(UpdateBookRequest $request, Book $book)
    {
        $book = $this->bookService->updateBook(
            $book, 
            $request->validated(), 
            $request->has('authors') ? $request->authors : null, 
            $request->has('subjects') ? $request->subjects : null
        );

        return response()->json($book->load(['authors', 'subjects']));
    }

    public function destroy(Book $book)
    {
        $this->bookService->deleteBook($book);
        return response()->json(null, 204);
    }
}
