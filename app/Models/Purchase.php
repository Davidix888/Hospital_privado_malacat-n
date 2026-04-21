<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Purchase extends Model
{
    protected $table = 'compra';

    protected $primaryKey = 'id_compra';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'id_usuario',
        'fecha',
        'total',
        'estado_entrega',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'total' => 'decimal:2',
            'estado_entrega' => 'string',
            'estado' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_proveedor', 'id_proveedor');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function details(): HasMany
    {
        return $this->hasMany(PurchaseDetail::class, 'id_compra', 'id_compra');
    }

    public function getItemsCountAttribute(): int
    {
        return (int) $this->details->sum('cantidad');
    }

    public function getIsDeliveredAttribute(): bool
    {
        return $this->estado_entrega === 'entregada';
    }

    public function getDeliveryLabelAttribute(): string
    {
        return $this->is_delivered ? 'Entregada' : 'Pendiente';
    }

    public function getDeliveryTextClassAttribute(): string
    {
        return $this->is_delivered ? 'text-emerald-600' : 'text-amber-600';
    }

    public function getSystemStatusLabelAttribute(): string
    {
        return $this->estado ? 'Activa' : 'Inactiva';
    }
}
