<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Helpers\ApiResponse;
use App\Models\SubjectType;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubjectTypeController extends Controller
{
    public function index()
    {
        $types = SubjectType::with('educationLevels')->get();
        return ApiResponse::success($types);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subject_types,name',
        ]);

        $type = SubjectType::create(['name' => $request->name]);
        return ApiResponse::success($type, 'Subject type created', 201);
    }

    public function show($id)
    {
        $type = SubjectType::with('educationLevels')->find($id);
        if (!$type) return ApiResponse::error('Subject type not found', 404);

        return ApiResponse::success($type);
    }

    public function update(Request $request, $id)
    {
        $type = SubjectType::find($id);
        if (!$type) return ApiResponse::error('Subject type not found', 404);

        $request->validate([
            'name' => ['required','string', Rule::unique('subject_types','name')->ignore($type->id)],
        ]);

        $type->update(['name' => $request->name]);
        return ApiResponse::success($type, 'Subject type updated');
    }

    public function destroy($id)
    {
        $type = SubjectType::find($id);
        if (!$type) return ApiResponse::error('Subject type not found', 404);

        $type->delete();
        return ApiResponse::success(null, 'Subject type deleted');
    }
}

