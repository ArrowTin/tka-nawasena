<?php


use App\Http\Controllers\Api\{
    CategoryController,
    EducationLevelController,
    SubjectController,
    QuestionTypeController,
    QuestionController,
    ExamController,
    StudentController,
    SubjectTypeController
};
use App\Http\Controllers\Server\ExamStudentController;
use Illuminate\Support\Facades\Route;

// ----------------------
// MANAGEMENT (ADMIN/TEACHER)
// ----------------------
// 'supervisor.jwt'
Route::prefix('management')->middleware([])->group(function () {

    Route::apiResource('education-levels', EducationLevelController::class);
    Route::apiResource('subject-types', SubjectTypeController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('question-types', QuestionTypeController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('exams', ExamController::class);

    // Tambah & Hapus soal dalam ujian
    Route::post('exams/{exam}/sync-questions', [ExamController::class, 'syncQuestion']);
});

// ----------------------
// SISWA
// ----------------------
Route::prefix('student')->middleware('student.jwt')->group(function () {
    Route::get('exams', [StudentController::class, 'listExams']);
    Route::get('exams/{exam}', [StudentController::class, 'examDetail']);
    Route::post('exams/{exam}/start', [StudentController::class, 'startExam']);
    Route::get('exams/attempts/{attempt}/get-answer', [StudentController::class, 'getExamQuestions']);
    Route::post('exams/attempts/{attempt}/save-answer/{question}', [StudentController::class, 'submitAnswer']);
    Route::post('exams/attempts/{attempt}/finish', [StudentController::class, 'finishExam']);
    Route::get('exams/attempts/{attempt}/review', [StudentController::class, 'reviewExam']);
    Route::get('exams/{exam}/ranking', [StudentController::class, 'ranking']);
});


// ----------------------
// SERVER
// ----------------------
// Route::middleware('service.jwt')->get('/exams', [ExamStudentController::class, 'exams']);
Route::middleware('service.jwt')->post('/exams/add-student', [ExamStudentController::class, 'addStudent']);
Route::middleware('service.jwt')->post('/exams/toggle-student-status', [ExamStudentController::class, 'toggleStudentStatus']);

