<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicinePresentation extends Model
{
    protected $table = 'medicamento_presentacion';

    protected $primaryKey = 'id_medicamento_presentacion';

    public $timestamps = false;

    protected $fillable = [
        'id_medicamento',
        'id_presentacion',
        'precio_venta',
        'stock_minimo',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'precio_venta' => 'decimal:2',
            'stock_minimo' => 'integer',
            'estado' => 'boolean',
        ];
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'id_medicamento', 'id_medicamento');
    }

    public function presentation(): BelongsTo
    {
        return $this->belongsTo(Presentation::class, 'id_presentacion', 'id_presentacion');
    }

    public function purchaseDetails(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'id_medicamento_presentacion', 'id_medicamento_presentacion');
    }

    public function getDisplayNameAttribute(): string
    {
        $medicine = $this->medicine?->nombre ?? 'Medicamento';
        $presentation = $this->presentation?->nombre ?? 'Presentacion';

        return trim($medicine.' - '.$presentation);
    }
}
