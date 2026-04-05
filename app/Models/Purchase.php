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
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha' => 'datetime',
            'total' => 'decimal:2',
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
}
