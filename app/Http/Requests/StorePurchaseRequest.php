<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StorePurchaseRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'id_proveedor' => ['required', 'integer', 'exists:proveedor,id_proveedor'],
            'id_medicamento_presentacion' => ['required', 'integer', 'exists:medicamento_presentacion,id_medicamento_presentacion'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'precio_compra' => ['required', 'numeric', 'gt:0'],
            'fecha' => ['required', 'date'],
            'numero_lote' => ['required', 'string', 'max:80'],
            'fecha_ingreso' => ['required', 'date'],
            'fecha_vencimiento' => ['required', 'date', 'after_or_equal:fecha_ingreso'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function messages(): array
    {
        return [
            'id_proveedor.required' => 'Debes seleccionar un proveedor.',
            'id_medicamento_presentacion.required' => 'Debes seleccionar el medicamento y la presentacion.',
            'cantidad.required' => 'La cantidad es obligatoria.',
            'cantidad.min' => 'La cantidad debe ser mayor que cero.',
            'precio_compra.required' => 'El precio de compra es obligatorio.',
            'precio_compra.gt' => 'El precio de compra debe ser mayor que cero.',
            'fecha.required' => 'Debes indicar la fecha de la compra.',
            'numero_lote.required' => 'Debes indicar el numero de lote.',
            'fecha_ingreso.required' => 'Debes indicar la fecha de ingreso al inventario.',
            'fecha_vencimiento.required' => 'Debes indicar la fecha de vencimiento.',
            'fecha_vencimiento.after_or_equal' => 'La fecha de vencimiento no puede ser menor que la fecha de ingreso.',
            'estado.required' => 'Debes seleccionar el estado de la compra.',
        ];
    }
}
