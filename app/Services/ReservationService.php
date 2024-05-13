<?php

namespace App\Services;

use App\Models\Reservation;

class ReservationService
{
    public function list()
    {

        $reservations = Reservation::all();
        return json_encode($reservations);
    }

    public function listOne($id)
    {

        $reservation = Reservation::where('id', $id)->with('book', 'student')->get();
        return json_encode($reservation);
    }

    public function create($data)
    {

        Reservation::create([

            'book_id' => $data['book_id'],
            'student_id' => $data['student_id'],
            'return_date' => $data['return_date'],
        ]);
    }

    public function edit($id)
    {

        $reservation = Reservation::find($id);
        $reservation['returned'] = true;
        $reservation->save();
    }

    public function delete($id)
    {

        $reservation = Reservation::find($id);

        if (isset($reservation)) {
            $reservation->delete();
        } else {
            return 'No hay reserva con esta id';
        }
    }

    public function filter($bookId, $studentId)
    {
        $reservations = Reservation::all();
        if ( isset($bookId) && isset($studentId) ){

            $reservations = Reservation::where('book_id', $bookId)
            ->where('student_id', $studentId)
            ->get();
        } elseif (isset($bookId) && !isset($studentId)) {

            $reservations = Reservation::where('book_id', $bookId)
            ->get();
        } elseif (!isset($bookId) && isset($studentId)) {

            $reservations = Reservation::where('student_id', $studentId)
            ->get();
        }


        return json_encode($reservations);
    }
}
