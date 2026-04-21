<?php

namespace App\Http\Requests;

use App\Models\Inventory;
use App\Models\Patient;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Validator;

class StoreSaleRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'modo_paciente' => ['nullable', 'in:ninguno,existente,nuevo'],
            'id_paciente' => ['nullable', 'integer', 'exists:paciente,id_paciente', 'required_if:modo_paciente,existente', 'exclude_unless:modo_paciente,existente'],
            'nombres_paciente' => ['nullable', 'string', 'max:120', 'required_if:modo_paciente,nuevo', 'exclude_unless:modo_paciente,nuevo'],
            'apellidos_paciente' => ['nullable', 'string', 'max:120', 'required_if:modo_paciente,nuevo', 'exclude_unless:modo_paciente,nuevo'],
            'fecha_nacimiento_paciente' => ['nullable', 'date', 'before_or_equal:today', 'required_if:modo_paciente,nuevo', 'exclude_unless:modo_paciente,nuevo'],
            'sexo_paciente' => ['nullable', 'string', 'in:Masculino,Femenino,Otro', 'required_if:modo_paciente,nuevo', 'exclude_unless:modo_paciente,nuevo'],
            'direccion_paciente' => ['nullable', 'string', 'max:180', 'required_if:modo_paciente,nuevo', 'exclude_unless:modo_paciente,nuevo'],
            'id_lote' => ['required', 'integer', 'exists:lote,id_lote'],
            'cantidad' => ['required', 'integer', 'min:1'],
            'fecha' => ['required', 'date'],
            'estado' => ['required', 'boolean'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            if ($validator->errors()->isNotEmpty()) {
                return;
            }

            if (($this->input('modo_paciente') ?? 'ninguno') === 'existente') {
                $patient = Patient::query()
                    ->where('id_paciente', (int) $this->input('id_paciente'))
                    ->first();

                if (! $patient || ! $patient->estado) {
                    $validator->errors()->add('id_paciente', 'Debes seleccionar un paciente activo para registrar la venta.');
                }
            }

            $inventory = Inventory::query()
                ->with(['lot.purchaseDetail.medicinePresentation.medicine', 'lot.purchaseDetail.medicinePresentation.presentation'])
                ->where('id_lote', (int) $this->input('id_lote'))
                ->first();

            if (! $inventory) {
                $validator->errors()->add('id_lote', 'El lote seleccionado no tiene inventario disponible.');

                return;
            }

            if (! ($inventory->lot?->estado ?? false)) {
                $validator->errors()->add('id_lote', 'El lote seleccionado no esta disponible para la venta.');
            }

            if (($inventory->lot?->fecha_vencimiento?->isBefore(today())) ?? false) {
                $validator->errors()->add('id_lote', 'El lote seleccionado ya esta vencido.');
            }

            if ($inventory->cantidad_actual < (int) $this->input('cantidad')) {
                $validator->errors()->add('cantidad', 'La cantidad solicitada supera el stock disponible del lote seleccionado.');
            }

            if (! ($inventory->medicine_presentation?->estado ?? false) || ! ($inventory->medicine_presentation?->medicine?->estado ?? false)) {
                $validator->errors()->add('id_lote', 'El medicamento seleccionado no esta disponible para la venta.');
            }
        });
    }

    public function messages(): array
    {
        return [
            'modo_paciente.in' => 'La opcion de paciente seleccionada no es valida.',
            'id_paciente.required_if' => 'Debes seleccionar un paciente existente.',
            'nombres_paciente.required_if' => 'Debes ingresar los nombres del paciente.',
            'apellidos_paciente.required_if' => 'Debes ingresar los apellidos del paciente.',
            'fecha_nacimiento_paciente.required_if' => 'Debes ingresar la fecha de nacimiento del paciente.',
            'sexo_paciente.required_if' => 'Debes seleccionar el sexo del paciente.',
            'direccion_paciente.required_if' => 'Debes ingresar la direccion del paciente.',
            'id_lote.required' => 'Debes seleccionar un lote disponible.',
            'cantidad.required' => 'La cantidad a vender es obligatoria.',
            'cantidad.min' => 'La cantidad a vender debe ser mayor que cero.',
            'fecha.required' => 'Debes indicar la fecha de la venta.',
            'estado.required' => 'Debes indicar el estado de la venta.',
        ];
    }
}
