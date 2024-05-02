<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentObservationRequest;
use App\Services\StudentsService;

class StudentOverservationController extends Controller
{
    public function __construct(
        protected StudentsService $studentsService
    ){}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentObservationRequest $request, $id)
    {

        //esto va en el show de student
        return $this->studentsService->createStudentObservation($id, $request->all());
    }
    public function listStudentObservations($studentId)
    {
        return $this->studentsService->listStudentObservations($studentId);
    }


    /**
     * Update the specified resource in storage.
     */
    // TODO: No usas el student id , eliminar
    public function update(StudentObservationRequest $request, $studentId, $observationId)
    {
        return $this->studentsService->updateStudentObservation($observationId, $request->all());
    }
    /**
     * Remove the specified resource from storage.
     */

     public function destroy($studentId, $observationId)
     {
         return $this->studentsService->deleteStudentObservation($observationId);
     }


    // TODO: No usas el student id , eliminar
     public function update_workaround(StudentObservationRequest $request, $studentId, $observationId)
     {
         return $this->update($request, $studentId, $observationId);
     }
}
