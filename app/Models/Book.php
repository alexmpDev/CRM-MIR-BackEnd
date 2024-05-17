<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'isbn',
        'gender',
        'qr'
    ];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}