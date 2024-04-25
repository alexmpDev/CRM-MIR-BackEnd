<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentsRequest;
use App\Models\Student;
use App\Services\StudentsService;

class StudentController extends Controller
{   

    public function __construct(
        protected StudentsService $studentsService,
    ) {}
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $students = Student::all();
        return json_encode($students);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentsRequest $request)
    {

        return  $this->studentsService->createStudent(
            $request->all()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentsRequest $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
