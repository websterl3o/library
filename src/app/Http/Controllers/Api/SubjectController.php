<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use App\Services\SubjectService;
use App\Http\Requests\StoreSubjectRequest;
use App\Http\Requests\UpdateSubjectRequest;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    protected $subjectService;

    public function __construct(SubjectService $subjectService)
    {
        $this->subjectService = $subjectService;
    }

    public function index()
    {
        $perPage = request('per_page', 25);
        $sortBy = request('sortBy', 'description');
        $sortType = request('sortType', 'asc');

        $subjects = $this->subjectService->getPaginated($perPage, $sortBy, $sortType);
        return response()->json($subjects);
    }

    public function store(StoreSubjectRequest $request)
    {
        $subject = $this->subjectService->createSubject($request->validated());
        return response()->json($subject, 201);
    }

    public function update(UpdateSubjectRequest $request, Subject $subject)
    {
        $subject = $this->subjectService->updateSubject($subject, $request->validated());
        return response()->json($subject);
    }

    public function destroy(Subject $subject)
    {
        $this->subjectService->deleteSubject($subject);
        return response()->json(['message' => 'Subject and related books deleted successfully.']);
    }
}
