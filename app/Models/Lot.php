<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Lot extends Model
{
    protected $table = 'lote';

    protected $primaryKey = 'id_lote';

    public $timestamps = false;

    protected $fillable = [
        'id_detalle_compra',
        'numero_lote',
        'fecha_vencimiento',
        'fecha_ingreso',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_vencimiento' => 'date',
            'fecha_ingreso' => 'date',
            'estado' => 'boolean',
        ];
    }

    public function purchaseDetail(): BelongsTo
    {
        return $this->belongsTo(PurchaseDetail::class, 'id_detalle_compra', 'id_detalle_compra');
    }

    public function inventory(): HasOne
    {
        return $this->hasOne(Inventory::class, 'id_lote', 'id_lote');
    }
}
