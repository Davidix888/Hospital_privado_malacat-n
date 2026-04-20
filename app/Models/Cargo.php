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

    public const USER_MANAGEMENT_OPTIONS = [
        [
            'nombre' => 'Farmacéutico',
            'descripcion' => 'Cargo base disponible para la gestión de usuarios.',
        ],
        [
            'nombre' => 'Técnico de laboratorio',
            'descripcion' => 'Cargo base disponible para la gestión de usuarios.',
        ],
        [
            'nombre' => 'Licenciado',
            'descripcion' => 'Cargo base disponible para la gestión de usuarios.',
        ],
        [
            'nombre' => 'Administrador',
            'descripcion' => 'Cargo base disponible para la gestión de usuarios.',
        ],
    ];

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

    /**
     * @return array<int, string>
     */
    public static function userManagementNames(): array
    {
        return array_column(self::USER_MANAGEMENT_OPTIONS, 'nombre');
    }

    public static function syncUserManagementOptions(): void
    {
        foreach (self::USER_MANAGEMENT_OPTIONS as $option) {
            self::query()->updateOrCreate(
                ['nombre' => $option['nombre']],
                ['descripcion' => $option['descripcion']],
            );
        }
    }
}
