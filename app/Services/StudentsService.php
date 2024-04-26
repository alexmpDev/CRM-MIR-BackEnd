<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;
use App\Models\Student;

class StudentsService
{
    public function list()
    {

        $students = Student::all();
        return json_encode($students);
    }

    public function listOne($id)
    {

        $student = Student::where('id', $id)->get();
        return json_encode($student);
    }

    public function create($data)
    {
        $photoPath = null;
        if (isset($data['photo'])) {
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

    public function edit($data, $id){

        $student = Student::find($id);
        $student->name = $data['name'];
        $student->class = $data['class'];
        if (isset($data['photo'])) {
            isset($student->photo) ? Storage::delete("/public/" .$student->photo) : "";
            $photoPath = $this->savePhoto($data['photo']);
            $student->photo = $photoPath;
        }

        $student->save();
        
    }

    public function delete($id){
        $student = Student::find($id);
        if (isset($student)) {
            $student->delete();
            if (isset($student->photo)){
    
                Storage::delete("/public/" .$student->photo);
            }
        } else {
            return 'No hay estudiante con esta id';
        }
        
        
    }
}
