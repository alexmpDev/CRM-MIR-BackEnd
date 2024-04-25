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

        return $this->studentsService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StudentsRequest $request)
    {

        return  $this->studentsService->create(
            $request->all()
        );
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->studentsService->listOne($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StudentsRequest $request, string $id)
    {
        return $this->studentsService->edit($request->all(), $id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->studentsService->delete($id);
    }

    public function update_workaround(StudentsRequest $request, $id)
    {
        return $this->update($request, $id);
    }
}
