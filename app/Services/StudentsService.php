<?php

namespace App\Services;

use App\Mail\WelcomeStudentMail;
use App\Models\PhoneInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\StudentObservation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class StudentsService
{
    public function list()
    {
        $students = Student::with('course')->get();
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
            'surname1' => $data['surname1'],
            'surname2' => $data['surname2'],
            'email' => $data['email'],
            'dni' => $data['dni'],
            'birthDate' => $data['birthDate'],
            'course_id' => $data['course_id'],
            'photo' => $photoPath,
            'leave' => $data['leave'],
        ]);

         // Llama a la función para guardar el código QR después de haber creado el estudiante
        $qrPath = $this->saveQr($student->id);

        // Actualiza el campo 'qr' en la tabla de estudiantes con la ruta del código QR
        $student->update(['qr' => $qrPath]);



       
        Mail::to('adria96.mp@gmail.com')->send(new WelcomeStudentMail($student, $qrPath));
        


        return response()->json($student, 201);


    }



    private function saveQr($studentId){
        $url = 'http://127.0.0.1:8000/api/students/' . $studentId;
        $image = QrCode::format('png')->generate($url);
        $output_file = 'public/qr/students/' . time() . '.png';
        Storage::disk('local')->put($output_file, $image);
        return $output_file;
    }


    public function edit($data, $id){

        $student = Student::find($id);
        $student->name = $data['name'];
        $student->surname1 = $data['surname1'];
        $student->surname2 = $data['surname2'];
        $student->dni = $data['dni'];
        $student->birthDate = $data['birthDate'];
        $student->curs = $data['course_id'];
        $student->leave = $data['leave'];
        if (isset($data['photo'])) {
            isset($student->photo) ? Storage::delete("/public/" .$student->photo) : "";
            $photoPath = $this->savePhoto($data['photo']);
            $student->photo = $photoPath;
        }

        $student->save();

    }

    public function createStudentObservation($data)
    {
        $student = Student::find($data['student_id']);
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

    public function createPhoneInfo($data)
    {
        $student = Student::find($data['student_id']);
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

    private function savePhoto($photo)
    {

        $photoPath = $photo->store('photos', 'public');
        return $photoPath;
    }

}
