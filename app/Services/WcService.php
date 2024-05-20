<?php

namespace App\Services;

use App\Models\WcPass;
use Carbon\Carbon;
use DateTime;

class WcService
{
    public function list() {

        $wcPass = WcPass::with('student')->get();
        return json_encode($wcPass);
    }

    public function listOne($id) {

        $wcPass = WcPass::where('id', $id)->get();
        return json_encode($wcPass);
    }


    public function create($data) {
        // Primero, comprobar si ya hay un pase válido
        $currentStatus = $this->passControl($data['student_id']);
        $status = json_decode($currentStatus, true);

        // Si hay un pase válido, no permitir crear otro
        if ($status['status']) {
            return json_encode([
                'status' => false,
                'message' => 'Este estudiante ya tiene un wcpass válido y no se puede crear otro.'
            ]);
        }

        // Si no hay pase válido, proceder a crear uno nuevo
        WcPass::create([
            'student_id' => $data['student_id'],
            'teacher' => $data['teacher'],
            'valid_until' =>Carbon::now()->addHours(1)
        ]);

        return json_encode([
            'status' => true,
            'message' => 'Nuevo wcpass creado exitosamente.'
        ]);
    }


    public function edit($data, $id) {

        $wcPass = WcPass::find($id);
        $wcPass['student_id'] = $data['student_id'];
        $wcPass['teacher'] = $data['teacher'];

        $wcPass->save();
    }

    public function delete($id) {

        $wcPass = WcPass::find($id);
        if (isset($wcPass)) {
            $wcPass->delete();
        } else {
            return 'No hay pase de baño con esta id';
        }
    }

    public function passControl($studentId) {
        $now = Carbon::now();
        $wcPass = WcPass::where('student_id', $studentId)
                        ->where('valid_until', '>', $now)
                        ->first();
        
        if ($wcPass) {
            return json_encode([
                'status' => true,
                'message' => 'El estudiante todavía tiene un wcpass válido.',
                'wcPass' => $wcPass
            ]);
        } else {
            return json_encode([
                'status' => false,
                'message' => 'No hay wcpass válido para este estudiante.'
            ]);
        }
    }

}
