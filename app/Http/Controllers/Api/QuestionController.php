<?php

namespace App\Http\Controllers\Api;

use App\Models\{Question, QuestionOption, QuestionCorrectAnswer};
use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class QuestionController extends Controller
{
    public function index()
    {
        return ApiResponse::success(
            Question::with(['subject', 'type', 'options', 'correctAnswers'])->get()
        );
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
            $question->load('subject', 'type', 'options', 'correctAnswers')
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
            'correct_option_ids' => 'nullable|array'
        ]);

        // Update gambar jika ada yang baru
        if ($request->hasFile('question_image')) {
            if ($question->question_image && Storage::disk('public')->exists($question->question_image)) {
                Storage::disk('public')->delete($question->question_image);
            }
            $data['question_image'] = $request->file('question_image')->store('questions', 'public');
        }

        DB::transaction(function () use ($data, $question) {
            // update question utama
            $question->update($data);

            // update options & correct answers jika dikirim
            if (isset($data['options'])) {
                // hapus semua option lama & correct answer lama
                QuestionOption::where('question_id', $question->id)->delete();
                QuestionCorrectAnswer::where('question_id', $question->id)->delete();

                // buat ulang opsi baru
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
