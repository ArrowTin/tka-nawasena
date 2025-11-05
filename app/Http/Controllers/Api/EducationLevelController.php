<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EducationLevelController extends Controller
{
    public function index()
    {
        $levels = EducationLevel::with('subjectTypes')->get();
        return ApiResponse::success($levels);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:education_levels,name',
        ]);

        $level = EducationLevel::create(['name' => $request->name]);
        return ApiResponse::success($level, 'Education level created', 201);
    }

    public function show($id)
    {
        $level = EducationLevel::with('subjectTypes')->find($id);
        if (!$level) return ApiResponse::error('Education level not found', 404);

        return ApiResponse::success($level);
    }

    public function update(Request $request, $id)
    {
        $level = EducationLevel::find($id);
        if (!$level) return ApiResponse::error('Education level not found', 404);

        $request->validate([
            'name' => ['required','string', Rule::unique('education_levels','name')->ignore($level->id)],
        ]);

        $level->update(['name' => $request->name]);
        return ApiResponse::success($level, 'Education level updated');
    }

    public function destroy($id)
    {
        $level = EducationLevel::find($id);
        if (!$level) return ApiResponse::error('Education level not found', 404);

        $level->delete();
        return ApiResponse::success(null, 'Education level deleted');
    }
}

