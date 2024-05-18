<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id'); // Obtiene el ID del usuario de la ruta (en caso de actualización)

        return [
            'email' => 'required|email|unique:users,email,' . $userId,
            'name' => 'required|string|max:255',
            'password' => 'nullable|string|min:6', // Hacemos que la contraseña sea opcional para la actualización
            'role_id' => 'required|integer'
        ];
    }
}
