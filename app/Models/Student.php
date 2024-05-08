<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'surname1',
        'surname2',
        'dni',
        'birthDate',
        'course_id',
        'photo',
        'leave',
        'qr'
    ];

    
    public function course()
    {
        return $this->belongsTo(Course::class);
    }


    public function observations()
    {
        return $this->hasMany(StudentObservation::class);
    }

    public function phones()
    {
        return $this->hasMany(PhoneInfo::class);
    }
}
