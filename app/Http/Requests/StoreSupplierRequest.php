<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreSupplierRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => [
                'required',
                'string',
                'max:120',
                Rule::unique('proveedor', 'nombre'),
            ],
            'direccion' => ['required', 'string', 'max:150'],
            'correo' => ['nullable', 'email', 'max:120', Rule::unique('proveedor', 'correo')],
            'telefono' => ['required', 'string', 'max:30'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'El nombre del proveedor es obligatorio.',
            'nombre.unique' => 'Ya existe un proveedor registrado con ese nombre.',
            'direccion.required' => 'La direccion del proveedor es obligatoria.',
            'correo.email' => 'El correo del proveedor debe tener un formato valido.',
            'correo.unique' => 'Ese correo ya esta registrado para otro proveedor.',
            'telefono.required' => 'El numero de telefono del proveedor es obligatorio.',
            'estado.required' => 'Debes seleccionar el estado del proveedor.',
        ];
    }
}
