<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PurchaseDetail extends Model
{
    protected $table = 'detalle_compra';

    protected $primaryKey = 'id_detalle_compra';

    public $timestamps = false;

    protected $fillable = [
        'id_compra',
        'id_medicamento_presentacion',
        'cantidad',
        'precio_compra',
        'subtotal',
    ];

    protected function casts(): array
    {
        return [
            'cantidad' => 'integer',
            'precio_compra' => 'decimal:2',
            'subtotal' => 'decimal:2',
        ];
    }

    public function purchase(): BelongsTo
    {
        return $this->belongsTo(Purchase::class, 'id_compra', 'id_compra');
    }

    public function medicinePresentation(): BelongsTo
    {
        return $this->belongsTo(MedicinePresentation::class, 'id_medicamento_presentacion', 'id_medicamento_presentacion');
    }

    public function lots(): HasMany
    {
        return $this->hasMany(Lot::class, 'id_detalle_compra', 'id_detalle_compra');
    }
}
