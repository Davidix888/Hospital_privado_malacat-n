<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    protected $table = 'paciente';

    protected $primaryKey = 'id_paciente';

    public $timestamps = false;

    protected $fillable = [
        'nombres',
        'apellidos',
        'fecha_nacimiento',
        'sexo',
        'direccion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'fecha_nacimiento' => 'date',
            'estado' => 'boolean',
        ];
    }

    public function getFullNameAttribute(): string
    {
        return trim($this->nombres.' '.$this->apellidos);
    }
}
