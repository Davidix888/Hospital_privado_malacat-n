<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateManagedUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, array<int, \Illuminate\Contracts\Validation\ValidationRule|string>>
     */
    public function rules(): array
    {
        /** @var User $managedUser */
        $managedUser = $this->route('user');

        return [
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuario', 'username')->ignore($managedUser->getKey(), 'id_usuario'),
            ],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
            'estado' => ['required', 'boolean'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'id_rol.required' => 'Debes seleccionar un rol.',
            'username.required' => 'El nombre de usuario es obligatorio.',
            'username.unique' => 'Ese nombre de usuario ya esta registrado.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
        ];
    }
}
