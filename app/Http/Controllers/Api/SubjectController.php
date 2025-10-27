<?php

namespace App\Http\Controllers\Api;

use App\Models\Subject;
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    public function index()
    {
        return ApiResponse::success(Subject::with('category')->get());
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);
        $data['code'] = Str::slug($request->name,'-');

        return ApiResponse::success(Subject::create($data), 'Subject created', 201);
    }

    public function show(Subject $subject)
    {
        return ApiResponse::success($subject->load('category'));
    }

    public function update(Request $request, Subject $subject)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'description' => 'nullable|string'
        ]);
        $data['code'] = Str::slug($request->name,'-');

        $subject->update($data);
        return ApiResponse::success($subject, 'Subject updated');
    }

    public function destroy(Subject $subject)
    {
        $subject->delete();
        return ApiResponse::success(null, 'Subject deleted');
    }
}
