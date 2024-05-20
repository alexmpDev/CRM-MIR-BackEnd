<?php

namespace App\Services;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class ReservationService
{
    public function list()
    {
        try {
            $reservations = Reservation::with(['student', 'book'])->get();
            return response()->json($reservations);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list reservations', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOne($id)
    {
        try {
            $reservation = Reservation::with(['student', 'book'])->findOrFail($id);
            return response()->json($reservation);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Reservation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve reservation', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        try {
            $reservation = Reservation::create([
                'book_id' => $data['book_id'],
                'student_id' => $data['student_id'],
                'return_date' => $data['return_date'],
            ]);
            return response()->json($reservation, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create reservation', 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->update(['returned' => true]);
            return response()->json(['message' => 'Reservation updated successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Reservation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update reservation', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $reservation = Reservation::findOrFail($id);
            $reservation->delete();
            return response()->json(['message' => 'Reservation deleted successfully'], 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Reservation not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete reservation', 'message' => $e->getMessage()], 500);
        }
    }

    public function filter($bookId, $studentId)
    {
        try {
            $query = Reservation::with(['student', 'book']);

            if (isset($bookId) && isset($studentId)) {
                $query->where('book_id', $bookId)->where('student_id', $studentId);
            } elseif (isset($bookId)) {
                $query->where('book_id', $bookId);
            } elseif (isset($studentId)) {
                $query->where('student_id', $studentId);
            }

            $reservations = $query->get();
            return response()->json($reservations);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to filter reservations', 'message' => $e->getMessage()], 500);
        }
    }
}
