<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        $list = Category::with(['educationLevel', 'subjectType'])->get();
        return ApiResponse::success($list);
    }

    public function store(Request $request)
    {
        $request->validate([
            'education_level_id' => 'required|exists:education_levels,id',
            'subject_type_id' => 'required|exists:subject_types,id',
        ]);

        $exists = Category::where('education_level_id', $request->education_level_id)
            ->where('subject_type_id', $request->subject_type_id)
            ->first();

        if ($exists) return ApiResponse::error('This category already exists', 400);

        $category = Category::create([
            'education_level_id' => $request->education_level_id,
            'subject_type_id' => $request->subject_type_id,
        ]);

        return ApiResponse::success($category, 'Category created', 201);
    }

    public function show($id)
    {
        $category = Category::with(['educationLevel', 'subjectType'])->find($id);
        if (!$category) return ApiResponse::error('Category not found', 404);

        return ApiResponse::success($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::find($id);
        if (!$category) return ApiResponse::error('Category not found', 404);

        $request->validate([
            'education_level_id' => 'required|exists:education_levels,id',
            'subject_type_id' => 'required|exists:subject_types,id',
        ]);

        $exists = Category::where('education_level_id', $request->education_level_id)
            ->where('subject_type_id', $request->subject_type_id)
            ->where('id', '!=', $id)
            ->first();

        if ($exists) return ApiResponse::error('This category already exists', 400);

        $category->update([
            'education_level_id' => $request->education_level_id,
            'subject_type_id' => $request->subject_type_id,
        ]);

        return ApiResponse::success($category, 'Category updated');
    }

    public function destroy($id)
    {
        $category = Category::find($id);
        if (!$category) return ApiResponse::error('Category not found', 404);

        $category->delete();
        return ApiResponse::success(null, 'Category deleted');
    }
}
