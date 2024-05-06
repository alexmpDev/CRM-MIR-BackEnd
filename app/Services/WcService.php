<?php

namespace App\Services;

use App\Models\WcPass;
use Carbon\Carbon;
use DateTime;

class WcService
{
    public function list() {

        $wcPass = WcPass::all();
        return json_encode($wcPass);
    }

    public function listOne($id) {

        $wcPass = WcPass::where('id', $id)->get();
        return json_encode($wcPass);
    }

    public function create($data) {
        $date = new DateTime();
        WcPass::create([
            'student_id' => $data['student_id'],
            'teacher' => $data['teacher'],
            'valid_until' => $date->modify('+ 1 hour')
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

}
