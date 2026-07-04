<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Book;
use App\Models\Author;
use App\Models\Subject;
use App\Models\VwBooksWithAuthor;
use App\Models\VwBooksWithSubject;

class DashboardController extends Controller
{
    public function countBooks()
    {
        return response()->json(['count' => Book::count()]);
    }

    public function countAuthors()
    {
        return response()->json(['count' => Author::count()]);
    }

    public function countSubjects()
    {
        return response()->json(['count' => Subject::count()]);
    }

    public function booksByAuthor()
    {
        return response()->json(VwBooksWithAuthor::all());
    }

    public function booksBySubject()
    {
        return response()->json(VwBooksWithSubject::all());
    }
}
