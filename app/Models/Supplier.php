<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Supplier extends Model
{
    protected $table = 'proveedor';

    protected $primaryKey = 'id_proveedor';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'direccion',
        'correo',
        'telefono',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function purchases(): HasMany
    {
        return $this->hasMany(Purchase::class, 'id_proveedor', 'id_proveedor');
    }

    public function email(): HasOne
    {
        return $this->hasOne(SupplierEmail::class, 'id_proveedor', 'id_proveedor');
    }

    public function phone(): HasOne
    {
        return $this->hasOne(SupplierPhone::class, 'id_proveedor', 'id_proveedor');
    }

    public function getContactEmailAttribute(): ?string
    {
        return $this->correo ?: $this->email?->correo;
    }

    public function getContactPhoneAttribute(): ?string
    {
        return $this->telefono ?: $this->phone?->numero;
    }
}
