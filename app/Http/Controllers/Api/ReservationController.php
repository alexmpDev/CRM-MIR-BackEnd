<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\ReservationRequest;
use App\Services\ReservationService;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function __construct(
        protected  ReservationService $reservationService,
    ) {
    }
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return $this->reservationService->list();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReservationRequest $request)
    {
        return $this->reservationService->create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return $this->reservationService->listOne($id);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
         $this->reservationService->edit($id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        return $this->reservationService->delete($id);
    }

    public function update_workaround(Request $request, $id)
    {

        return $this->update($request, $id);
    }

    public function filter(Request $request)
    {

        return $this->reservationService->filter(
            $request->input('book_id'),
            $request->input('student_id')
        );
    }
}
