<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Inventory extends Model
{
    protected $table = 'inventario';

    protected $primaryKey = 'id_inventario';

    public $timestamps = false;

    protected $fillable = [
        'id_lote',
        'cantidad_actual',
        'fecha_actualizacion',
    ];

    protected function casts(): array
    {
        return [
            'cantidad_actual' => 'integer',
            'fecha_actualizacion' => 'datetime',
        ];
    }

    public function lot(): BelongsTo
    {
        return $this->belongsTo(Lot::class, 'id_lote', 'id_lote');
    }

    public function getMedicinePresentationAttribute(): ?MedicinePresentation
    {
        return $this->lot?->purchaseDetail?->medicinePresentation;
    }

    public function getMedicineNameAttribute(): string
    {
        return $this->medicine_presentation?->medicine?->nombre ?? 'Sin medicamento';
    }

    public function getPresentationNameAttribute(): string
    {
        return $this->medicine_presentation?->presentation?->nombre ?? 'Sin presentacion';
    }

    public function getLotNumberAttribute(): string
    {
        return $this->lot?->numero_lote ?? 'Sin lote';
    }

    public function getExpiresAtAttribute(): ?string
    {
        return $this->lot?->fecha_vencimiento?->format('d/m/Y');
    }

    public function getStockMinimoAttribute(): int
    {
        return (int) ($this->medicine_presentation?->stock_minimo ?? 0);
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->cantidad_actual <= $this->stock_minimo;
    }

    public function getSalesOptionLabelAttribute(): string
    {
        $price = number_format((float) ($this->medicine_presentation?->precio_venta ?? 0), 2);
        $expiresAt = $this->expires_at ?? 'Sin fecha';

        return sprintf(
            '%s - %s | Lote %s | Stock %d | Vence %s | Precio Q %s',
            $this->medicine_name,
            $this->presentation_name,
            $this->lot_number,
            $this->cantidad_actual,
            $expiresAt,
            $price
        );
    }
}
