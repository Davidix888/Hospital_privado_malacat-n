<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    protected $table = 'usuario';

    protected $primaryKey = 'id_usuario';

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'id_empleado',
        'id_rol',
        'username',
        'password',
        'estado',
        'fecha_creacion',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
    ];

    public function rol(): BelongsTo
    {
        return $this->belongsTo(Role::class, 'id_rol', 'id_rol');
    }

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'id_empleado', 'id_empleado');
    }

    public function getRoleNameAttribute(): string
    {
        return $this->rol?->nombre ?? 'Sin rol asignado';
    }

    public function getEmployeeNameAttribute(): string
    {
        return $this->empleado?->full_name ?? 'Sin empleado asignado';
    }

    public function hasAbility(string $ability): bool
    {
        if (! $this->estado || ! $this->rol) {
            return false;
        }

        return $this->rol->hasAbility($ability);
    }

    public function canAccessModule(string $module): bool
    {
        return $this->hasAbility($module.'.view');
    }

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
            'fecha_creacion' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
