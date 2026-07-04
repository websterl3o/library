<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Services\AuthorService;
use App\Http\Requests\StoreAuthorRequest;
use App\Http\Requests\UpdateAuthorRequest;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    protected $authorService;

    public function __construct(AuthorService $authorService)
    {
        $this->authorService = $authorService;
    }

    public function index()
    {
        $perPage = request('per_page', 25);
        $sortBy = request('sortBy', 'name');
        $sortType = request('sortType', 'asc');

        $authors = $this->authorService->getPaginated($perPage, $sortBy, $sortType);
        return response()->json($authors);
    }

    public function store(StoreAuthorRequest $request)
    {
        $author = $this->authorService->createAuthor($request->validated());
        return response()->json($author, 201);
    }

    public function update(UpdateAuthorRequest $request, Author $author)
    {
        $author = $this->authorService->updateAuthor($author, $request->validated());
        return response()->json($author);
    }

    public function destroy(Author $author)
    {
        $this->authorService->deleteAuthor($author);
        return response()->json(['message' => 'Author and related books deleted successfully.']);
    }
}
