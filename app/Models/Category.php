<?php

// app/Models/Category.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Category extends Model
{
    protected $fillable = ['education_level_id', 'subject_type_id'];

    public function subjects(): HasMany
    {
        return $this->hasMany(Subject::class, 'category_id');
    }

    public function educationLevel(): BelongsTo
    {
        return $this->belongsTo(EducationLevel::class);
    }

    public function subjectType(): BelongsTo
    {
        return $this->belongsTo(SubjectType::class);
    }
}

