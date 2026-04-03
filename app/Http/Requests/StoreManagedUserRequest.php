<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Validator;

class StoreManagedUserRequest extends FormRequest
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
        return [
            'id_rol' => ['required', 'integer', 'exists:rol,id_rol'],
            'username' => [
                'required',
                'string',
                'max:50',
                Rule::unique('usuario', 'username'),
            ],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
            'estado' => ['required', 'boolean'],
            'id_cargo' => ['nullable', 'integer', 'exists:cargo,id_cargo'],
            'cargo_nombre' => ['nullable', 'string', 'max:80'],
            'cargo_descripcion' => ['nullable', 'string', 'max:255'],
            'nombres' => ['required', 'string', 'max:80'],
            'apellidos' => ['required', 'string', 'max:80'],
            'dpi' => ['required', 'string', 'size:13', Rule::unique('empleado', 'dpi')],
            'direccion' => ['required', 'string', 'max:150'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if (! $this->filled('id_cargo') && ! $this->filled('cargo_nombre')) {
                $validator->errors()->add('id_cargo', 'Debes seleccionar un cargo o escribir uno nuevo para el empleado.');
            }
        });
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
            'password.required' => 'La contrasena es obligatoria.',
            'password.confirmed' => 'La confirmacion de contrasena no coincide.',
            'password.min' => 'La contrasena debe tener al menos 8 caracteres.',
            'estado.required' => 'Debes seleccionar el estado inicial del usuario.',
            'nombres.required' => 'Los nombres del empleado son obligatorios.',
            'apellidos.required' => 'Los apellidos del empleado son obligatorios.',
            'dpi.required' => 'El DPI del empleado es obligatorio.',
            'dpi.unique' => 'Ya existe un empleado registrado con ese DPI.',
            'direccion.required' => 'La direccion del empleado es obligatoria.',
            'id_cargo.exists' => 'El cargo seleccionado no existe.',
        ];
    }
}
