<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

use App\Http\Requests\PhoneInfoRequest;
use App\Services\StudentsService;

class PhoneInfoController extends Controller
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
    public function store(PhoneInfoRequest $request)
    {

        //esto va en el show de student
        return $this->studentsService->createPhoneInfo($request->all());
    }

    public function show($id)
    {
        return $this->studentsService->listOnePhone($id);
    }

    public function listPhoneInfo($studentId)
    {
        return $this->studentsService->listStudentPhoneInfo($studentId);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(PhoneInfoRequest $request, $phoneInfoId)
    {
        return $this->studentsService->updatePhoneInfo($phoneInfoId, $request->all());
    }
    /**
     * Remove the specified resource from storage.
     */

     public function destroy($phoneInfoId)
     {
         return $this->studentsService->deletePhoneInfo($phoneInfoId);
     }


     public function update_workaround(PhoneInfoRequest $request, $id)
     {
         return $this->update($request,  $id);
     }
}
