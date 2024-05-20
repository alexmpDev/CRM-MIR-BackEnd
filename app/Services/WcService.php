<?php

namespace App\Services;

use App\Models\WcPass;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class WcService
{
    public function list()
    {
        try {
            $wcPass = WcPass::with('student')->get();
            return response()->json($wcPass);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list wc passes', 'message' => $e->getMessage()], 500);
        }
    }

    public function listOne($id)
    {
        try {
            $wcPass = WcPass::with('student')->findOrFail($id);
            return response()->json($wcPass);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'WcPass not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve wc pass', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        try {
            $currentStatus = $this->passControl($data['student_id']);
            $status = json_decode($currentStatus->getContent(), true);

            if ($status['status']) {
                return response()->json([
                    'status' => false,
                    'message' => 'Este estudiante ya tiene un wcpass válido y no se puede crear otro.'
                ], 400);
            }

            $wcPass = WcPass::create([
                'student_id' => $data['student_id'],
                'teacher' => $data['teacher'],
                'valid_until' => Carbon::now()->addHour()
            ]);

            return response()->json([
                'status' => true,
                'message' => 'Nuevo wcpass creado exitosamente.',
                'wcPass' => $wcPass
            ], 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create wc pass', 'message' => $e->getMessage()], 500);
        }
    }

    public function edit($data, $id)
    {
        try {
            $wcPass = WcPass::findOrFail($id);
            $wcPass->update([
                'student_id' => $data['student_id'],
                'teacher' => $data['teacher']
            ]);

            return response()->json(['message' => 'WcPass updated successfully', 'wcPass' => $wcPass], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'WcPass not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update wc pass', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $wcPass = WcPass::findOrFail($id);
            $wcPass->delete();
            return response()->json(['message' => 'WcPass deleted successfully'], 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'WcPass not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete wc pass', 'message' => $e->getMessage()], 500);
        }
    }

    public function passControl($studentId)
    {
        try {
            $now = Carbon::now();
            $wcPass = WcPass::where('student_id', $studentId)
                ->where('valid_until', '>', $now)
                ->first();

            if ($wcPass) {
                return response()->json([
                    'status' => true,
                    'message' => 'El estudiante todavía tiene un wcpass válido.',
                    'wcPass' => $wcPass
                ], 200);
            } else {
                return response()->json([
                    'status' => false,
                    'message' => 'No hay wcpass válido para este estudiante.'
                ], 200);
            }
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to check wc pass status', 'message' => $e->getMessage()], 500);
        }
    }
}
