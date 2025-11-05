<?php

// app/Models/SubjectType.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubjectType extends Model
{
    protected $fillable = ['name'];

    public function educationLevels(): BelongsToMany
    {
        return $this->belongsToMany(EducationLevel::class,'categories','subject_type_id','education_level_id');
    }
}
