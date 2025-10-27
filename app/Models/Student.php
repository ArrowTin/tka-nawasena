<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['user_id', 'full_name', 'gender', 'birth_date', 'school_name', 'grade_level'];

    
    public function attempts() {
        return $this->hasMany(ExamAttempt::class);
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_students')
                    ->withPivot('is_active')
                    ->withTimestamps();
    }
}
