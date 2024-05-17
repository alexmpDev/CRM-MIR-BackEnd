<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'curs',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_courses');
    }

}
