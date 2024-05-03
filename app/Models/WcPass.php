<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WcPass extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'teacher'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
