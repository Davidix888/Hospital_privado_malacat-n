<?php

namespace App\Models;

use Database\Factories\RoleFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Role extends Model
{
    /** @use HasFactory<RoleFactory> */
    use HasFactory;

    protected $table = 'rol';

    protected $primaryKey = 'id_rol';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'id_rol', 'id_rol');
    }

    public function abilityKey(): string
    {
        return Str::of($this->nombre)
            ->ascii()
            ->lower()
            ->replace(' ', '_')
            ->value();
    }

    public function abilities(): array
    {
        return config('access.roles.'.$this->abilityKey(), []);
    }

    public function hasAbility(string $ability): bool
    {
        return in_array($ability, $this->abilities(), true);
    }
}
