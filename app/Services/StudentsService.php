<?php

namespace App\Services;

use App\Mail\WelcomeStudentMail;
use App\Models\PhoneInfo;
use Illuminate\Support\Facades\Storage;
use App\Models\Student;
use App\Models\StudentObservation;
use Illuminate\Support\Facades\Mail;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class StudentsService
{
    public function list()
    {
        try {
            $students = Student::with('course')->get();
            return response()->json($students);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list students', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOne($id)
    {
        try {
            $student = Student::with('course')->findOrFail($id);
            return response()->json($student);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve student', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOnePhone($id)
    {
        try {
            $phoneInfo = PhoneInfo::findOrFail($id);
            return response()->json($phoneInfo);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Phone information not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve phone information', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOneObservation($id)
    {
        try {
            $observation = StudentObservation::findOrFail($id);
            return response()->json($observation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Observation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve observation', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        try {
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

            $qrPath = $this->saveQr($student->id);
            $student->update(['qr' => $qrPath]);

            Mail::to($student->email)->send(new WelcomeStudentMail($student, $qrPath));

            return response()->json($student, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create student', 'message' => $e->getMessage()], 500);
        }
    }

    private function saveQr($studentId)
    {
        try {
            $url = env('BASE_URL', 'http://127.0.0.1:8000') . '/api/students/' . $studentId;
            $image = QrCode::format('png')->generate($url);
            $output_file = 'public/qr/students/' . time() . '.png';
            Storage::disk('local')->put($output_file, $image);
            return $output_file;
        } catch (Exception $e) {
            throw new Exception('Failed to generate QR code: ' . $e->getMessage());
        }
    }

    public function edit($data, $id)
    {
        try {
            $student = Student::findOrFail($id);
            $student->update([
                'name' => $data['name'],
                'surname1' => $data['surname1'],
                'surname2' => $data['surname2'],
                'email' => $data['email'],
                'dni' => $data['dni'],
                'birthDate' => $data['birthDate'],
                'course_id' => $data['course_id'],
                'leave' => $data['leave'],
            ]);

            if (isset($data['photo'])) {
                isset($student->photo) ? Storage::delete("/public/" . $student->photo) : "";
                $photoPath = $this->savePhoto($data['photo']);
                $student->update(['photo' => $photoPath]);
            }

            return response()->json($student, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update student', 'message' => $e->getMessage()], 500);
        }
    }

    public function createStudentObservation($data)
    {
        try {
            $student = Student::findOrFail($data['student_id']);

            $observation = StudentObservation::create([
                'student_id' => $student->id,
                'observation' => $data['observation']
            ]);

            return response()->json($observation, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create observation', 'message' => $e->getMessage()], 500);
        }
    }

    public function updateStudentObservation($id, $data)
    {
        try {
            $observation = StudentObservation::findOrFail($id);
            $observation->update(['observation' => $data['observation']]);
            return response()->json($observation, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Observation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update observation', 'message' => $e->getMessage()], 500);
        }
    }

    public function deleteStudentObservation($observationId)
    {
        try {
            $observation = StudentObservation::findOrFail($observationId);
            $observation->delete();
            return response()->json(['message' => 'Observation deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Observation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete observation', 'message' => $e->getMessage()], 500);
        }
    }

    public function listStudentObservations($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $observations = $student->observations()->get();
            return response()->json($observations, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve observations', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $student = Student::findOrFail($id);
            if (isset($student->photo)) {
                Storage::delete("/public/" . $student->photo);
            }
            $student->delete();
            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete student', 'message' => $e->getMessage()], 500);
        }
    }

    public function createPhoneInfo($data)
    {
        try {
            $student = Student::findOrFail($data['student_id']);
            $phoneInfo = PhoneInfo::create([
                'student_id' => $student->id,
                'name' => $data['name'],
                'phone' => $data['phone']
            ]);
            return response()->json($phoneInfo, 201);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create phone information', 'message' => $e->getMessage()], 500);
        }
    }

    public function updatePhoneInfo($id, $data)
    {
        try {
            $phoneInfo = PhoneInfo::findOrFail($id);
            $phoneInfo->update([
                'name' => $data['name'],
                'phone' => $data['phone']
            ]);
            return response()->json($phoneInfo, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Phone information not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update phone information', 'message' => $e->getMessage()], 500);
        }
    }

    public function deletePhoneInfo($phoneInfoId)
    {
        try {
            $phoneInfo = PhoneInfo::findOrFail($phoneInfoId);
            $phoneInfo->delete();
            return response()->json(['message' => 'Phone information deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Phone information not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete phone information', 'message' => $e->getMessage()], 500);
        }
    }

    public function listStudentPhoneInfo($studentId)
    {
        try {
            $student = Student::findOrFail($studentId);
            $phones = $student->phones()->get();
            return response()->json($phones, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Student not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve phone information', 'message' => $e->getMessage()], 500);
        }
    }

    private function savePhoto($photo)
    {
        try {
            $photoPath = $photo->store('photos', 'public');
            return $photoPath;
        } catch (Exception $e) {
            throw new Exception('Failed to save photo: ' . $e->getMessage());
        }
    }
}
