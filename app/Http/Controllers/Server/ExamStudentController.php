<?php

namespace App\Http\Controllers\Server;

use App\Helpers\ApiResponse;
use App\Http\Controllers\Controller;
use App\Models\Exam;
use Illuminate\Http\Request;
use App\Models\ExamStudent;
use App\Models\Student;

class ExamStudentController extends Controller
{
    public function exams()
    {
        return ApiResponse::success(Exam::with(['category'])->get());
    }
    /**
     * Menambahkan siswa ke ujian
     */
    public function addStudent(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'user_id' => 'required|exists:students,user_id',
            'is_active' => 'sometimes|boolean',
        ]);

        $examId = $request->exam_id;
        $student = Student::where('user_id',$request->user_id)->first();
        if (!$student) {
            $student = Student::create([
                'user_id'   => $request->user_id,
            ]);
        }
        
        $isActive = $request->input('is_active', true);

        $examStudent = ExamStudent::where('exam_id', $examId)->where('student_id', $student->id)->first();
        if (!$examStudent) {
            $examStudent = ExamStudent::create(
                ['exam_id' => $examId, 'student_id' => $student->id,'is_active' => $isActive]
            );
            return ApiResponse::success($examStudent,'Student added to exam successfully',201);
        }

        $examStudent->is_active = true;
        $examStudent->save();
        
        return ApiResponse::success($examStudent,'Student added to exam successfully',200);
    }

    /**
     * Mengubah status aktif/non-aktif siswa di ujian
     * Request Body:
     * {
     *   "exam_id": 1,
     *   "user_id": 123,
     * }
     */
    public function toggleStudentStatus(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'user_id' => 'required|exists:students,user_id',
        ]);

        $student = Student::where('user_id',$request->user_id)->first();

        $examStudent = ExamStudent::where('exam_id', $request->exam_id)
                                  ->where('student_id', $student->id)
                                  ->first();

        if (!$examStudent) {
            return ApiResponse::error($examStudent,'Student not found in this exam',400);
        }

        $examStudent->is_active = !$examStudent->is_active;
        $examStudent->save();

        $status = $examStudent->is_active ? 'activated' : 'deactivated';


        return ApiResponse::success($examStudent, "Student successfully {$status}",200);

    }
}
