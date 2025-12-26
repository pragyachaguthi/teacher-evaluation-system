<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Evaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id', 'teacher_id', 'criteria1', 'criteria2', 'criteria3', 'feedback'
    ];

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id');
    }
}
