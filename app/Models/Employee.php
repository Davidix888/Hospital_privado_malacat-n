<?php

namespace App\Models;

use Database\Factories\EmployeeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Employee extends Model
{
    /** @use HasFactory<EmployeeFactory> */
    use HasFactory;

    protected $table = 'empleado';

    protected $primaryKey = 'id_empleado';

    public $timestamps = false;

    protected $fillable = [
        'id_cargo',
        'nombres',
        'apellidos',
        'dpi',
        'direccion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function cargo(): BelongsTo
    {
        return $this->belongsTo(Cargo::class, 'id_cargo', 'id_cargo');
    }

    public function user(): HasOne
    {
        return $this->hasOne(User::class, 'id_empleado', 'id_empleado');
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->nombres.' '.$this->apellidos);
    }
}
