<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Models\Book;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {
        return response()->json(Subject::orderBy('description')->get());
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:20'
        ]);

        $subject = Subject::create($validated);

        return response()->json($subject, 201);
    }

    public function update(Request $request, Subject $subject)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:20'
        ]);

        $subject->update($validated);

        return response()->json($subject);
    }

    public function destroy(Subject $subject)
    {
        $bookIds = $subject->books()->pluck('books.id');
        Book::whereIn('id', $bookIds)->delete();
        
        $subject->delete();

        return response()->json(['message' => 'Subject and related books deleted successfully.']);
    }
}
