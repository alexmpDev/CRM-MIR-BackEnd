<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'event_date'
    ];

    // Relación muchos-a-muchos con Course

    public function courses()
    {
        return $this->belongsToMany(Course::class, 'event_courses');
    }

    public function tickets()
    {
        return $this->hasMany(EventTicket::class);
    }
}
