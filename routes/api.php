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

    Route::get('education-levels/subject-types', [EducationLevelController::class,'subjectTypes']);
    Route::post('education-levels/{id}/add-subject-types', [EducationLevelController::class,'addSubjectType']);
    Route::apiResource('education-levels', EducationLevelController::class);
    Route::get('subject-types/education-levels', [SubjectTypeController::class,'educationLevels']);
    Route::post('subject-types/{id}/add-education-levels', [SubjectTypeController::class,'addeducationLevels']);
    Route::apiResource('subject-types', SubjectTypeController::class);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('subjects', SubjectController::class);
    Route::apiResource('question-types', QuestionTypeController::class);
    Route::apiResource('questions', QuestionController::class);
    Route::apiResource('exams', ExamController::class);
    Route::get('exams/{exam}/questions', [ExamController::class,'questions']);
    Route::post('exams/{exam}/add-questions', [ExamController::class,'syncQuestion']);

    // Tambah & Hapus soal dalam ujian
    Route::post('exams/{exam}/sync-questions', [ExamController::class, 'syncQuestion']);
});

// ----------------------
// SISWA
// ----------------------
// 'student.jwt'
Route::prefix('student')->middleware([])->group(function () {
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
// 'service.jwt'
// Route::middleware('service.jwt')->get('/exams', [ExamStudentController::class, 'exams']);
Route::middleware([])->post('/exams/add-student', [ExamStudentController::class, 'addStudent']);
Route::middleware([])->post('/exams/toggle-student-status', [ExamStudentController::class, 'toggleStudentStatus']);

