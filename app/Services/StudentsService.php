<?php

namespace App\Services;

use App\Models\Student;

class StudentsService
{
    
    public function createStudent($data)
    {
        if ( isset($data['photo']) ) {
            $photoPath = $this->savePhoto($data['photo']);
        }

        Student::create([
            'name' => $data['name'],
            'class' => $data['class'],
            'photo' => $photoPath,
        ]);
    }
    
    private function savePhoto($photo)
    {
        $photoPath = $photo->store('photos', 'public');
        return $photoPath;
    }
}