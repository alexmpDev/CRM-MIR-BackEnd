<?php

namespace App\Services;

use App\Models\PhoneInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\StudentObservation;

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

        $student = Student::create([
            'name' => $data['name'],
            'class' => $data['class'],
            'photo' => $photoPath,
        ]);

        // Crea el BiblioPass asociado al estudiante, lo creamos de esta manerapor que hay una relación en el modelo
        $student->biblioPass()->create();

        // Carga la relación BiblioPass
        $student->load('biblioPass');


        return response()->json($student, 201);

       
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

    public function createStudentObservation($id, $data)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        $observation = StudentObservation::create([
            'student_id' => $student->id,
            'observation' => $data['observation']
        ]);
    
        return response()->json($observation, 201);
    }

    public function updateStudentObservation($id, $data)
    {
        $observation = StudentObservation::findOrFail($id);
        
        $observation->update([
            'observation' => $data['observation']
        ]);

        return response()->json($observation, 200);
    }


    public function deleteStudentObservation($observationId)
    {
        $observation = StudentObservation::find($observationId);
        if (!$observation) {
            return response()->json(['message' => 'Observation not found'], 404);
        }

        $observation->delete();
        return response()->json(['message' => 'Observation deleted successfully'], 200);
    }

    public function listStudentObservations($studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        $observations = $student->observations()->get();
    
        return response()->json($observations, 200);
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

    //phone info

    public function createPhoneInfo($id, $data)
    {
        $student = Student::find($id);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        $phoneInfo = PhoneInfo::create([
            'student_id' => $student->id,
            'name' => $data['name'],
            'phone' => $data['phone']
        ]);
    
        return response()->json($phoneInfo, 201);
    }

    public function updatePhoneInfo($id, $data)
    {
        $phoneInfo = PhoneInfo::findOrFail($id);
        
        $phoneInfo->update([
            'name' => $data['name'],
            'phone' => $data['phone']
        ]);

        return response()->json($phoneInfo, 200);
    }


    public function deletePhoneInfo($phoneInfoId)
    {
        $phoneInfo = PhoneInfo::find($phoneInfoId);
        if (!$phoneInfo) {
            return response()->json(['message' => 'Phone information not found'], 404);
        }

        $phoneInfo->delete();
        return response()->json(['message' => 'Phone information deleted successfully'], 200);
    }

    public function listStudentPhoneInfo($studentId)
    {
        $student = Student::find($studentId);
        if (!$student) {
            return response()->json(['message' => 'Student not found'], 404);
        }
    
        $phones = $student->phones()->get();
    
        return response()->json($phones, 200);
    }

}
