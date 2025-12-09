<?php

namespace App\Http\Controllers\Api;

use App\Models\{Exam, Question, Subject};
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\DataTableBuilder;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    public function index(Request $request)
    {
    
        if (request()->ajax()) {
            $builder = new DataTableBuilder(
                Exam::query()->with(['category', 'subject', 'questions'])
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

        
        $subjects = Subject::with('category')
                    ->get()
                    ->mapWithKeys(function ($item) {
                        $label = "{$item->name} - {$item->category->category_name}";
                        return [$item->id => $label];
                    });
        $questions    = Question::with('options.correctAnswer')->get();
    
        return view('master.exams.index', compact('subjects', 'questions'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
            // 'created_by' => 'required|exists:users,id',
        ]);
        $data['category_id']    = Subject::find($data['subject_id'])->category_id;
        
        return ApiResponse::success(Exam::create($data), 'Exam created', 201);
    }

    public function show(Exam $exam)
    {
        return ApiResponse::success($exam->load('questions'));
    }

    public function update(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'title' => 'required|string',
            'description' => 'nullable|string',
            'duration_minutes' => 'required|integer|min:1',
            'start_time' => 'nullable|date',
            'end_time' => 'nullable|date',
        ]);
        $data['category_id']    = Subject::find($data['subject_id'])->category_id;
        $exam->update($data);
        return ApiResponse::success($exam, 'Exam updated');
    }

    public function destroy(Exam $exam)
    {
        $exam->delete();
        return ApiResponse::success(null, 'Exam deleted');
    }
    
    public function questions(Exam $exam)
    {
        return ApiResponse::success(Question::with('options.correctAnswer')->where('subject_id',$exam->subject_id)->get());
    }

    public function syncQuestion(Request $request, Exam $exam)
    {
        $data = $request->validate([
            'questions' => 'required|array',
            'questions.*' => 'exists:questions,id',
        ]);

        $exam->questions()->sync($data['questions']);

        return ApiResponse::success(
            $exam->load('questions'),
            'Questions added to exam'
        );
    }

 
}
