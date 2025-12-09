<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Exam;
use App\Models\Subject;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        // FILTER
        $categoryId = $request->category_id;
        $subjectId  = $request->subject_id;
        $dateStart  = $request->date_start;
        $dateEnd    = $request->date_end;

        $query = Exam::with(['category', 'subject', 'attempts', 'questions']);

        if ($categoryId) $query->where('category_id', $categoryId);
        if ($subjectId)  $query->where('subject_id', $subjectId);
        if ($dateStart)  $query->whereDate('created_at', '>=', $dateStart);
        if ($dateEnd)    $query->whereDate('created_at', '<=', $dateEnd);

        $exams = $query->get();

        // Statistik
        $totalUjian     = $exams->count();
        $totalSoal      = $exams->sum(fn($e) => $e->questions->count());
        $totalAttempts  = $exams->sum(fn($e) => $e->attempts->count());
        $avgQuestions   = $totalUjian ? round($totalSoal / $totalUjian, 1) : 0;

        // Grafik: jumlah soal tiap ujian
        $chartLabels = $exams->pluck('title');
        $chartData   = $exams->map(fn($e) => $e->questions->count());

        // Grafik: jumlah attempt tiap ujian
        $chartAttempts = $exams->map(fn($e) => $e->attempts->count());

        session(['api_token' => $request->token]);

        return view('template', [
            'exams'      => $exams,
            'categories' => Category::all(),
            'subjects'   => Subject::all(),

            'totalUjian'    => $totalUjian,
            'totalSoal'     => $totalSoal,
            'totalAttempts' => $totalAttempts,
            'avgQuestions'  => $avgQuestions,

            'chartLabels'   => $chartLabels,
            'chartData'     => $chartData,
            'chartAttempts' => $chartAttempts,
        ]);

    }


    public function red()
    {
        return redirect()->away(env('NAWASENA_APP').'/operator/dashboard');
    }
}
