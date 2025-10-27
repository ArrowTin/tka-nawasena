<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class QuestionCorrectAnswer extends Model
{
    protected $fillable = ['question_id', 'option_id'];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

    public function option()
    {
        return $this->belongsTo(QuestionOption::class, 'option_id');
    }
}
