<?php

namespace App\Http\Controllers\Api;

use App\Models\QuestionType;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class QuestionTypeController extends Controller
{
    public function index()
    {
        return ApiResponse::success(QuestionType::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string', 'description' => 'nullable|string']);
        return ApiResponse::success(QuestionType::create($data), 'Question type created', 201);
    }

    public function show(QuestionType $questionType)
    {
        return ApiResponse::success($questionType);
    }

    public function update(Request $request, QuestionType $questionType)
    {
        $data = $request->validate(['name' => 'required|string', 'description' => 'nullable|string']);
        $questionType->update($data);
        return ApiResponse::success($questionType, 'Question type updated');
    }

    public function destroy(QuestionType $questionType)
    {
        $questionType->delete();
        return ApiResponse::success(null, 'Question type deleted');
    }
}
