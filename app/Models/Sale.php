<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Sale extends Model
{
    protected $table = 'venta';

    protected $primaryKey = 'id_venta';

    public $timestamps = false;

    protected $fillable = [
        'id_paciente',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'id_usuario', 'id_usuario');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'id_paciente', 'id_paciente');
    }

    public function details(): HasMany
    {
        return $this->hasMany(SaleDetail::class, 'id_venta', 'id_venta');
    }

    public function getItemsCountAttribute(): int
    {
        return (int) $this->details->sum('cantidad');
    }
}
