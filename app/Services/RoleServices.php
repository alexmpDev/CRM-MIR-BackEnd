<?php

namespace App\Services;

use App\Models\Role;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class RoleServices
{
    public function list()
    {
        try {
            $roles = Role::all();
            return response()->json($roles);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list roles', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        try {
            $role = Role::create([
                'name' => $data['name'],
            ]);
            return response()->json($role, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create role', 'message' => $e->getMessage()], 500);
        }
    }

    public function list_one($id)
    {
        try {
            $role = Role::findOrFail($id);
            return response()->json($role);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve role', 'message' => $e->getMessage()], 500);
        }
    }

    public function update($data, $id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->update([
                'name' => $data['name']
            ]);
            return response()->json($role, 200);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update role', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $role = Role::findOrFail($id);
            $role->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'Role not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete role', 'message' => $e->getMessage()], 500);
        }
    }
}
