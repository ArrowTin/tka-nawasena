<?php

namespace App\Http\Controllers\Api;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Subject;
use App\Services\DataTableBuilder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;

class SubjectController extends Controller
{
    /**
     * List all subjects
     */
    public function index(Request $request)
    {

        if (request()->ajax()) {
            $builder = new DataTableBuilder(
                Subject::query()->with('category.educationLevel','category.subjectType')
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

        $categories = Category::with('educationLevel','subjectType')
                    ->get()
                    ->mapWithKeys(function($c){
                        return [
                            $c->id => $c->educationLevel->name . ' - ' . $c->subjectType->name
                        ];
                    });
        return view('master.subjects.index',compact('categories'));

    }

    /**
     * Store a new subject
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
        ]);

        $subject = Subject::create([
            'code' => Str::slug($request->name),
            'name' => $request->name,
            'category_id' => $request->category_id,
            'description' => $request->description,
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
            'description' => 'required|string',
        ]);

        $subject->update([
            'code' => Str::slug($request->name),
            'name' => $request->name,
            'description' => $request->description,
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
