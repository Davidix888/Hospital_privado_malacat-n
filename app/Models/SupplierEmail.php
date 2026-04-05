<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SupplierEmail extends Model
{
    protected $table = 'correo_proveedor';

    protected $primaryKey = 'id_correo_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'id_proveedor',
        'correo',
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
