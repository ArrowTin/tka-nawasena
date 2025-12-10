<?php

namespace App\Http\Controllers\Api;

use App\Models\{Category, Question, QuestionOption, QuestionCorrectAnswer, QuestionType, Subject};
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Services\DataTableBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index(Request $request)
    {

        if (request()->ajax()) {
            $builder = new DataTableBuilder(
                Question::query()->with(['subject', 'type', 'options.correctAnswer'])
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
        $types    = QuestionType::pluck('name','id');
    
        return view('master.questions.index', compact('subjects', 'types'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'explanation' => 'nullable|string',
            'difficulty' => 'in:easy,medium,hard',
            'options' => 'nullable|array',
            'correct_option_ids' => 'nullable|array'
        ]);

        if ($request->hasFile('question_image')) {
            $path = $request->file('question_image')->store('questions', 'public');
            $data['question_image'] = $path;
        }

        /** @var \App\Models\Question|null $question */
        $question = null;

        DB::transaction(function () use ($data, &$question) {
            $question = Question::create($data);

            if (!empty($data['options'])) {
                foreach ($data['options'] as $opt) {
                    $option = QuestionOption::create([
                        'question_id' => $question->id,
                        'option_label' => $opt['label'] ?? null,
                        'option_text' => $opt['text'] ?? null,
                    ]);

                    if (!empty($data['correct_option_ids']) &&
                        in_array($opt['label'], $data['correct_option_ids'])) {
                        QuestionCorrectAnswer::create([
                            'question_id' => $question->id,
                            'option_id' => $option->id,
                        ]);
                    }
                }
            }
        });

        return ApiResponse::success(
            $question->load('options', 'correctAnswers'),
            'Question created',
            201
        );
    }

    public function show(Question $question)
    {
        return ApiResponse::success(
            $question->load('subject', 'type', 'options.correctAnswer')
        );
    }

    public function update(Request $request, Question $question)
    {
        $data = $request->validate([
            'subject_id' => 'required|exists:subjects,id',
            'question_type_id' => 'required|exists:question_types,id',
            'question_text' => 'required|string',
            'question_image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'explanation' => 'nullable|string',
            'difficulty' => 'in:easy,medium,hard',
            'options' => 'nullable|array',
            'correct_option_ids' => 'nullable|array'  // ← label array ("A", "B")
        ]);

        // Update gambar jika ada yang baru
        if ($request->hasFile('question_image')) {
            if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
                Storage::disk('public')->delete($question->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }

        DB::transaction(function () use ($data, $question) {

            $question->update($data);

            // Hapus semua opsi & jawaban benar lama
            QuestionOption::where('question_id', $question->id)->delete();
            QuestionCorrectAnswer::where('question_id', $question->id)->delete();

            $labelToNewId = []; // label → NEW id

            if (!empty($data['options'])) {
                foreach ($data['options'] as $opt) {

                    // buat opsi baru
                    $newOpt = QuestionOption::create([
                        'question_id'  => $question->id,
                        'option_label' => $opt['label'],   // contoh: "A"
                        'option_text'  => $opt['text'],
                    ]);

                    // simpan mapping label → id
                    $labelToNewId[$opt['label']] = $newOpt->id;
                }
            }

            // simpan jawaban benar berdasarkan LABEL
            if (!empty($data['correct_option_ids'])) {
                foreach ($data['correct_option_ids'] as $label) {

                    if (isset($labelToNewId[$label])) {
                        QuestionCorrectAnswer::create([
                            'question_id' => $question->id,
                            'option_id'   => $labelToNewId[$label],
                        ]);
                    }
                }
            }

        });

        return ApiResponse::success(
            $question->load('options', 'correctAnswers'),
            'Question updated successfully'
        );
    }


    public function destroy(Question $question)
    {
        if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
            Storage::disk('public')->delete($question->question_image);
        }

        $question->delete();
        return ApiResponse::success(null, 'Question deleted');
    }
}
