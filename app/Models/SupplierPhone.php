<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierPhone extends Model
{
    protected $table = 'telefono_proveedor';

    protected $primaryKey = 'id_telefono_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'numero',
        'tipo',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class, 'id_proveedor', 'id_proveedor');
    }
}
