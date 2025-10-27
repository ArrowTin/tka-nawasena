<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    protected $fillable = ['question_id', 'option_label', 'option_text'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function correctAnswer()
    {
        return $this->hasOne(QuestionCorrectAnswer::class, 'option_id');
    }
}
