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
            'modo_categoria' => ['required', 'in:existente,nueva'],
            'id_categoria' => ['nullable', 'integer', 'exists:categoria_medicamento,id_categoria', 'required_if:modo_categoria,existente', 'exclude_unless:modo_categoria,existente'],
            'nueva_categoria' => ['nullable', 'string', 'max:100', 'required_if:modo_categoria,nueva', 'exclude_unless:modo_categoria,nueva'],
            'descripcion_categoria' => ['nullable', 'string', 'max:255', 'exclude_unless:modo_categoria,nueva'],
            'modo_presentacion' => ['required', 'in:existente,nueva'],
            'id_presentacion' => ['nullable', 'integer', 'exists:presentacion,id_presentacion', 'required_if:modo_presentacion,existente', 'exclude_unless:modo_presentacion,existente'],
            'nueva_presentacion' => ['nullable', 'string', 'max:100', 'required_if:modo_presentacion,nueva', 'exclude_unless:modo_presentacion,nueva'],
            'descripcion_presentacion' => ['nullable', 'string', 'max:255', 'exclude_unless:modo_presentacion,nueva'],
            'precio_venta' => ['required', 'numeric', 'gt:0'],
            'stock_minimo' => ['required', 'integer', 'min:1'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'nombre.required' => 'Debes ingresar el nombre del medicamento.',
            'modo_categoria.required' => 'Debes indicar cómo registrar la categoría.',
            'id_categoria.required_if' => 'Selecciona una categoría existente.',
            'nueva_categoria.required_if' => 'Debes escribir la nueva categoría.',
            'modo_presentacion.required' => 'Debes indicar cómo registrar la presentación.',
            'id_presentacion.required_if' => 'Selecciona una presentación existente.',
            'nueva_presentacion.required_if' => 'Debes escribir la nueva presentación.',
            'precio_venta.required' => 'Debes ingresar el precio de venta.',
            'precio_venta.gt' => 'El precio de venta debe ser mayor que cero.',
            'stock_minimo.required' => 'Debes ingresar el stock mínimo.',
            'stock_minimo.min' => 'El stock mínimo debe ser al menos 1.',
            'estado.required' => 'Debes seleccionar el estado del medicamento.',
        ];
    }
}
