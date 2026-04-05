<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreMedicineCatalogRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'nombre' => ['required', 'string', 'max:120'],
            'descripcion' => ['nullable', 'string', 'max:255'],
            'id_categoria' => ['nullable', 'integer', 'exists:categoria_medicamento,id_categoria', 'required_without:nueva_categoria'],
            'nueva_categoria' => ['nullable', 'string', 'max:100', 'required_without:id_categoria'],
            'descripcion_categoria' => ['nullable', 'string', 'max:255'],
            'id_presentacion' => ['nullable', 'integer', 'exists:presentacion,id_presentacion', 'required_without:nueva_presentacion'],
            'nueva_presentacion' => ['nullable', 'string', 'max:100', 'required_without:id_presentacion'],
            'descripcion_presentacion' => ['nullable', 'string', 'max:255'],
            'precio_venta' => ['required', 'numeric', 'gt:0'],
            'stock_minimo' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Debes ingresar el nombre del medicamento.',
            'id_categoria.required_without' => 'Selecciona una categoria o registra una nueva.',
            'nueva_categoria.required_without' => 'Debes seleccionar o escribir una categoria.',
            'id_presentacion.required_without' => 'Selecciona una presentacion o registra una nueva.',
            'nueva_presentacion.required_without' => 'Debes seleccionar o escribir una presentacion.',
            'precio_venta.required' => 'Debes ingresar el precio de venta.',
            'precio_venta.gt' => 'El precio de venta debe ser mayor que cero.',
            'stock_minimo.required' => 'Debes ingresar el stock minimo.',
            'stock_minimo.min' => 'El stock minimo debe ser al menos 1.',
            'estado.required' => 'Debes seleccionar el estado del medicamento.',
        ];
    }
}
