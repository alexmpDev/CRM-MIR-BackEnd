<?php
namespace App\Services;
use App\Models\Role;

class RoleServices {
    public function list(){
        $roles = Role::all();
        return response()->json($roles);
    }

    public function create($data){
    
        $role = Role::create([
            'name' => $data['name'],
            
        ]);
        return response()->json($role, 201);
    }

    public function list_one($id){
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    public function update($data, $id){
        $role = Role::findOrFail($id);
        $role->update([
            'name'=> $data['name']
        ]);
        return response()->json($role, 200);
    }

    public function delete($id){
        $role = Role::findOrFail($id);
        $role->delete();

        return response()->json(null, 204);
    }
}