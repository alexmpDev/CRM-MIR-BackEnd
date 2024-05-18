<?php
namespace App\Services;
use Illuminate\Support\Facades\Validator;
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
        
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'password' => 'nullable|string|min:6',
            'role_id' => 'required|integer'
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }
    
        // Si la validación pasa, actualiza el usuario
        $updateData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'role_id' => $data['role_id']
        ];

        if (!empty($data['password'])) {
            $updateData['password'] = bcrypt($data['password']);
        }
        
        $user->update($updateData);
    
        return response()->json($user, 200);
    }

    public function delete($id){
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}