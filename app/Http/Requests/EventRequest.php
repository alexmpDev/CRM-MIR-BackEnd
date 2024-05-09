<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
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
        return [
            'name' => 'required|string|max:255', // Requerido, debe ser string y no mayor de 255 caracteres
            'description' => 'required|string|max:500', // Requerido, debe ser string y no mayor de 500 caracteres
            'event_date' => 'required|date', // Requerido, debe ser una fecha válida
        ];
    }
}
