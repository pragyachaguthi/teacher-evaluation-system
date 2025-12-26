<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProgramSemesterCode extends Model
{
    protected $fillable = ['faculty', 'program', 'semester', 'code'];
}
