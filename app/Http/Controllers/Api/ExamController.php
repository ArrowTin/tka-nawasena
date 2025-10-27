<?php

namespace App\Http\Controllers\Api;

use App\Models\{Exam, Question};
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index()
    {
        return ApiResponse::success(Exam::with(['category', 'subject', 'questions'])->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            // 'created_by' => 'required|exists:users,id',
        ]);
        return ApiResponse::success(Exam::create($data), 'Exam created', 201);
    }

    public function show(Exam $exam)
    {
        return ApiResponse::success($exam->load('questions'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'subject_id' => 'nullable|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);
        $exam->update($data);
        return ApiResponse::success($exam, 'Exam updated');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return ApiResponse::success(null, 'Exam deleted');
    }

    public function syncQuestion(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:questions,id',
        ]);

        $exam->questions()->sync($data['question_ids']);

        return ApiResponse::success(
            $exam->load('questions'),
            'Questions added to exam'
        );
    }

 
}
