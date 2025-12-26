<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name','program','semester', 'user_id', 'program_id', 'semester_id' , 'faculty'];

    // Relation to the User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relation to Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relation to Semester
    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}

