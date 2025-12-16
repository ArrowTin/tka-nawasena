<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\{
    Exam, ExamAttempt, ExamQuestion, ExamAnswer, Question, QuestionOption
};
use Carbon\Carbon;

class StudentController extends Controller
{
    // ============================
    // LIST UJIAN
    // ============================
    public function listExams(Request $request)
    {
        $exams = Exam::with(['category', 'subject'])
            ->whereHas('students',function($es) use($request){
                return $es->where('student_id',$request->student_id)->where('is_active',true);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $exams
        ]);
    }

    // ============================
    // DETAIL UJIAN
    // ============================
    public function examDetail($id)
    {
        $exam = Exam::with(['category', 'subject', 'questions.type','attempts'])
            ->find($id);

        if (!$exam) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Exam not found'
            ]);
        }

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $exam
        ]);
    }

    // ============================
    // MULAI UJIAN
    // ============================
    public function startExam(Request $request, $examId)
    {
        $studentId = $request->student_id;

        $exam = Exam::find($examId);
        if (!$exam) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Exam not found'
            ]);
        }

        // Cegah multiple attempt aktif
        $activeAttempt = ExamAttempt::where('student_id', $studentId)
            ->where('exam_id', $examId)
            ->whereNull('finished_at')
            ->first();

        if ($activeAttempt) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'You already have an active attempt'
            ]);
        }

        $attempt = ExamAttempt::create([
            'student_id' => $studentId,
            'exam_id' => $examId,
            'started_at' => Carbon::now(),
        ]);

        // Jadwal otomatis mengakhiri ujian
        $endTime = Carbon::now()->addMinutes($exam->duration_minutes);

        // (Optional) Di sistem real bisa gunakan queue job Laravel untuk auto-finish

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => [
                'attempt' => $attempt,
                'exam_end_time' => $endTime
            ]
        ]);
    }

    // ============================
    // AMBIL SOAL SAAT UJIAN
    // ============================
    public function getExamQuestions($attemptId)
    {
        $attempt = ExamAttempt::whereNull('finished_at')->with('exam')->find($attemptId);
        if (!$attempt) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Attempt not found'
            ]);
        }

        $examQuestions = ExamQuestion::with([
            'question.type',
            'question.options'
        ])
            ->where('exam_id', $attempt->exam_id)
            ->orderBy('order_number')
            ->get();

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $examQuestions
        ]);
    }

    // ============================
    // SIMPAN JAWABAN SISWA
    // ============================
    public function submitAnswer(Request $request, $attemptId, $questionId)
    {
        $attempt = ExamAttempt::find($attemptId);
        if (!$attempt) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Attempt not found'
            ]);
        }

        $question = Question::find($questionId);
        if (!$question) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Question not found'
            ]);
        }

        $data = $request->validate([
            'selected_option_ids' => 'nullable|array',
            'answer_text' => 'nullable|string'
        ]);

        $selected = $data['selected_option_ids'] ?? [];
        $answerText = $data['answer_text'] ?? null;

        if (count($selected) > 1 && $question->type->name == 'Pilihan ganda') {
            return response()->json([
                'code' => 400,
                'status' => 'gagal',
                'message'   => 'opsi jawaban lebih dari satu'
            ]);
        }

        // Cek kebenaran
        $correctOptionIds = $question->correctAnswers()->pluck('option_id')->toArray();
        $isCorrect = false;

        if ($question->question_type_id !== null && !empty($selected)) {
            $isCorrect = collect($selected)->sort()->values()->toArray() === collect($correctOptionIds)->sort()->values()->toArray();
        }


        $existing = ExamAnswer::where('attempt_id', $attemptId)
            ->where('question_id', $questionId)
            ->first();
        
        if ($existing) {
            // Update jika sudah ada
            $existing->update([
                'selected_option_ids' => json_encode($selected),
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
            ]);
        } else {
            // Insert baru jika belum ada
            ExamAnswer::create([
                'attempt_id' => $attemptId,
                'question_id' => $questionId,
                'selected_option_ids' => json_encode($selected),
                'answer_text' => $answerText,
                'is_correct' => $isCorrect,
            ]);
        }
        

        return response()->json([
            'code' => 200,
            'status' => 'success',
            // 'data' => ['is_correct' => $isCorrect]
        ]);
    }

    // ============================
    // SELESAI UJIAN OTOMATIS
    // ============================
    public function finishExam($attemptId)
    {
        $attempt = ExamAttempt::with('answers')->find($attemptId);
    
        if (!$attempt) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Attempt not found'
            ]);
        }
    
        if ($attempt->finished_at) {
            return response()->json([
                'code' => 400,
                'status' => 'error',
                'message' => 'Exam already finished'
            ]);
        }
    
        // ✅ Ambil semua soal di ujian ini
        $examQuestions = ExamQuestion::where('exam_id', $attempt->exam_id)->pluck('id');
    
        // ✅ Ambil soal yang sudah dijawab
        $answeredQuestionIds = ExamAnswer::where('attempt_id', $attempt->id)
            ->pluck('question_id')
            ->toArray();
    
        // ✅ Cari soal yang belum dijawab
        $unansweredQuestions = $examQuestions->diff($answeredQuestionIds);
    
        // ✅ Simpan jawaban kosong untuk soal yang belum dijawab
        foreach ($unansweredQuestions as $questionId) {
            ExamAnswer::create([
                'attempt_id' => $attempt->id,
                'question_id' => $questionId,
                'answer' => null,
                'is_correct' => false,
            ]);
        }
    
        // ✅ Hitung total soal dan jumlah benar
        $totalQuestions = $examQuestions->count();
        $correctAnswers = ExamAnswer::where('attempt_id', $attempt->id)
            ->where('is_correct', true)
            ->count();
    
        // ✅ Hitung skor
        $score = $totalQuestions > 0 ? ($correctAnswers / $totalQuestions) * 100 : 0;
    
        // ✅ Update status ujian selesai
        $attempt->update([
            'finished_at' => now(),
            'score' => round($score, 2),
        ]);
    
        return response()->json([
            'code' => 200,
            'status' => 'success',
            'message' => 'Exam finished successfully',
            'data' => [
                'attempt_id' => $attempt->id,
                'score' => round($score, 2),
                'total_questions' => $totalQuestions,
                'correct_answers' => $correctAnswers,
                'unanswered' => count($unansweredQuestions),
            ],
        ]);
    }
    

    // ============================
    // PEMBAHASAN SOAL BERDASARKAN JAWABAN SISWA
    // ============================
    public function reviewExam($attemptId)
    {
        $attempt = ExamAttempt::with('answers')->find($attemptId);
        if (!$attempt) {
            return response()->json([
                'code' => 404,
                'status' => 'error',
                'message' => 'Attempt not found'
            ]);
        }

        $questions = ExamQuestion::with([
            'question.type',
            'question.options',
            'question.correctAnswers',
        ])->where('exam_id', $attempt->exam_id)->get();

        $review = $questions->map(function ($eq) use ($attempt) {
            $answer = $attempt->answers->where('question_id', $eq->question->id)->first();
            return [
                'type' => $eq->question->type->name,
                'question' => $eq->question->question_text,
                'options' => $eq->question->options,
                'correct' => $eq->question->correctAnswers->pluck('option_id'),
                'student_answer' => $answer ? json_decode($answer->selected_option_ids, true) : null,
                'is_correct' => $answer ? $answer->is_correct : null,
                'explanation' => $eq->question->explanation
            ];
        });

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $review
        ]);
    }

    // ============================
    // PERANKINGAN SKOR
    // ============================
    public function ranking(Request $request, $examId)
    {
        $perPage = $request->get('per_page', 10); // default 10 data per halaman

        $ranking = ExamAttempt::with('student:id,full_name')
            ->where('exam_id', $examId)
            ->whereNotNull('score')
            ->orderByDesc('score')
            ->paginate($perPage, ['id', 'student_id', 'score']); // hanya ambil field penting

        // Ubah format data agar hanya tampil nama & skor
        $data = $ranking->getCollection()->transform(function ($item) {
            return [
                'name' => $item->student->full_name ?? '-',
                'score' => (float) $item->score,
            ];
        });

        // Ganti collection di paginator dengan data yang sudah diformat
        $ranking->setCollection($data);

        return response()->json([
            'code' => 200,
            'status' => 'success',
            'data' => $ranking
        ]);
    }

}
