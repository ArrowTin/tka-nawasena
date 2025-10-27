<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
    {
        return ApiResponse::success(Category::all());
    }

    public function store(Request $request)
    {
        $data = $request->validate(['name' => 'required|string', 'description' => 'nullable|string']);
        $category = Category::create($data);
        return ApiResponse::success($category, 'Category created', 201);
    }

    public function show(Category $category)
    {
        return ApiResponse::success($category);
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate(['name' => 'required|string', 'description' => 'nullable|string']);
        $category->update($data);
        return ApiResponse::success($category, 'Category updated');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return ApiResponse::success(null, 'Category deleted');
    }
}
