<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExamStudent extends Model
{
    use HasFactory;

    protected $fillable = [
        'exam_id',
        'student_id',
        'is_active'
    ];

    // Relasi ke Exam
    public function exam()
    {
        return $this->belongsTo(Exam::class);
    }

    // Relasi ke Student
    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
