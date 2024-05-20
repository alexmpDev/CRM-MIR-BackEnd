<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class UserServices
{
    public function list()
    {
        try {
            $users = User::with('role')->get();
            return response()->json($users);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to list users', 'message' => $e->getMessage()], 500);
        }
    }

    public function create($data)
    {
        $validator = Validator::make($data, [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'role_id' => 'required|integer|exists:roles,id'
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 422);
        }

        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'role_id' => $data['role_id']
            ]);
            return response()->json($user, 201);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to create user', 'message' => $e->getMessage()], 500);
        }
    }

    public function list_one($id)
    {
        try {
            $user = User::with('role')->findOrFail($id);
            return response()->json($user);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to retrieve user', 'message' => $e->getMessage()], 500);
        }
    }

    public function update($data, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($data, [
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
                'password' => 'nullable|string|min:6',
                'role_id' => 'required|integer|exists:roles,id'
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

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
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to update user', 'message' => $e->getMessage()], 500);
        }
    }

    public function delete($id)
    {
        try {
            $user = User::findOrFail($id);
            $user->delete();
            return response()->json(null, 204);
        } catch (ModelNotFoundException $e) {
            return response()->json(['error' => 'User not found', 'message' => $e->getMessage()], 404);
        } catch (Exception $e) {
            return response()->json(['error' => 'Failed to delete user', 'message' => $e->getMessage()], 500);
        }
    }
}
