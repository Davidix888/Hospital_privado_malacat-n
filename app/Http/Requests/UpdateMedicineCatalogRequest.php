<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicineCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'precio_venta' => ['required', 'numeric', 'gt:0'],
            'stock_minimo' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'precio_venta.required' => 'Debes ingresar el precio de venta.',
            'precio_venta.gt' => 'El precio de venta debe ser mayor que cero.',
            'stock_minimo.required' => 'Debes ingresar el stock minimo.',
            'stock_minimo.min' => 'El stock minimo debe ser al menos 1.',
            'estado.required' => 'Debes seleccionar la disponibilidad del medicamento.',
        ];
    }
}
