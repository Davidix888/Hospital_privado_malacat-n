<?php

namespace App\Models;

use Database\Factories\CargoFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cargo extends Model
{
    /** @use HasFactory<CargoFactory> */
    use HasFactory;

    protected $table = 'cargo';

    protected $primaryKey = 'id_cargo';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class, 'id_cargo', 'id_cargo');
    }
}
