<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * List all subjects
     */
    public function index()
    {
        // Load relations category -> educationLevel & subjectType
        $subjects = Subject::with('category.educationLevel', 'category.subjectType')->get();
        return ApiResponse::success($subjects);
    }

    /**
     * Store a new subject
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subject = Subject::create([
            'code' => Str::slug($request->name),
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return ApiResponse::success($subject, 'Subject created', 201);
    }

    /**
     * Show a single subject
     */
    public function show($id)
    {
        $subject = Subject::with('category.educationLevel', 'category.subjectType')->find($id);

        if (!$subject) {
            return ApiResponse::error('Subject not found', 404);
        }

        return ApiResponse::success($subject);
    }

    /**
     * Update a subject
     */
    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return ApiResponse::error('Subject not found', 404);
        }

        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
        ]);

        $subject->update([
            'code' => Str::slug($request->name),
            'name' => $request->name,
            'category_id' => $request->category_id,
        ]);

        return ApiResponse::success($subject, 'Subject updated');
    }

    /**
     * Delete a subject
     */
    public function destroy($id)
    {
        $subject = Subject::find($id);
        if (!$subject) {
            return ApiResponse::error('Subject not found', 404);
        }

        $subject->delete();
        return ApiResponse::success(null, 'Subject deleted');
    }
}
