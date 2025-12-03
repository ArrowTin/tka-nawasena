<?php

namespace App\Http\Controllers\Api;


use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\EducationLevel;
use App\Models\SubjectType;
use App\Services\DataTableBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EducationLevelController extends Controller
{
    public function index(Request $request)
    {
        $builder = new DataTableBuilder(
            EducationLevel::query()->with('subjectTypes')
        );


        $sortBy = $request->sort_by ?? 'id';
      

        $data = $builder
            ->multiSearch($request->filters ?? [])
            ->search(['name'], $request->keyword)
            ->sort($sortBy, $request->sort_dir ?? 'asc')   
            ->apply(
                $request->page ?? 1,
                $request->per_page ?? 10
            );                                         

        return ApiResponse::success($data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:education_levels,name',
        ]);

        $level = EducationLevel::create(['name' => $request->name]);
        return ApiResponse::success($level, 'Education level created', 201);
    }

    public function addSubjectType(Request $request,$id) {

        $level = EducationLevel::find($id);
        if (!$level) return ApiResponse::error('Education level not found', 404);

        $request->validate([
            'subjectTypes' => 'required|array|min:1',
            'subjectTypes.*' => 'required|exists:subject_types,id',
        ]);

        $level->subjectTypes()->sync($request->subjectTypes);

        return ApiResponse::success($level, 'Tipe Mata Pelajaran Perbaharui');
    }

    public function show($id)
    {
        $level = EducationLevel::with('subjectTypes')->find($id);
        if (!$level) return ApiResponse::error('Education level not found', 404);

        return ApiResponse::success($level);
    }

    public function subjectTypes()
    {
        return ApiResponse::success(SubjectType::select('id','name')->get());
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

