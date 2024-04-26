<?php
namespace App\Services;
use App\Models\User;


class UserServices {

    public function list(){
        $users = User::with('role')->get();
        return response()->json($users);
    }

    public function create($data){
        
        $user = User::create([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password'=> $data['password'],
            'role_id'=>$data['role_id']
        ]);
        
        return response()->json($user, 201);
    }

    public function list_one($id){
        $user = User::with('role')->findOrFail($id);
        return response()->json($user);
    }

    public function update($data, $id){
        
        $user = User::findOrFail($id);
        $user->update([
            'name' => $data['name'],
            'email'=> $data['email'],
            'password'=> $data['password'],
            'role_id'=>$data['role_id']
        ]);

        return response()->json($user, 200);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}