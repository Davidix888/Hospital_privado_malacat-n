<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SaleDetail extends Model
{
    protected $table = 'detalle_venta';

    protected $primaryKey = 'id_detalle_venta';

    public $timestamps = false;

    protected $fillable = [
        'id_venta',
        'id_medicamento_presentacion',
        'cantidad',
        'precio_unitario',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_unitario' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function sale(): BelongsTo
    {
        return $this->belongsTo(Sale::class, 'id_venta', 'id_venta');
    }

    public function medicinePresentation(): BelongsTo
    {
        return $this->belongsTo(MedicinePresentation::class, 'id_medicamento_presentacion', 'id_medicamento_presentacion');
    }

    public function getMedicineNameAttribute(): string
    {
        return $this->medicinePresentation?->medicine?->nombre ?? 'Sin medicamento';
    }

    public function getPresentationNameAttribute(): string
    {
        return $this->medicinePresentation?->presentation?->nombre ?? 'Sin presentacion';
    }
}
