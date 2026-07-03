<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Author;
use App\Models\Book;
use Illuminate\Http\Request;

class AuthorController extends Controller
{
    public function index()
    {
        $query = Author::query();

        if (request()->has('sortBy') && request()->has('sortType')) {
            $query->orderBy(request('sortBy'), request('sortType'));
        } else {
            $query->orderBy('name');
        }

        $perPage = request('per_page', 25);
        return response()->json($query->paginate($perPage));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $author = Author::create($validated);

        return response()->json($author, 201);
    }

    public function update(Request $request, Author $author)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:40'
        ]);

        $author->update($validated);

        return response()->json($author);
    }

    public function destroy(Author $author)
    {
        $bookIds = $author->books()->pluck('books.id');
        Book::whereIn('id', $bookIds)->delete();
        
        $author->delete();

        return response()->json(['message' => 'Author and related books deleted successfully.']);
    }
}
