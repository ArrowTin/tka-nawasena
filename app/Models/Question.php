<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $fillable = [
        'subject_id',
        'question_type_id',
        'question_text',
        'question_image',
        'explanation',
        'difficulty'
    ];

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function type()
    {
        return $this->belongsTo(QuestionType::class, 'question_type_id');
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function correctAnswers()
    {
        return $this->hasMany(QuestionCorrectAnswer::class,'question_id');
    }

    public function exams()
    {
        return $this->belongsToMany(Exam::class, 'exam_questions')
                    ->withPivot('order_number')
                    ->withTimestamps();
    }
}
