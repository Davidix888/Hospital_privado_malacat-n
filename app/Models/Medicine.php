<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Medicine extends Model
{
    protected $table = 'medicamento';

    protected $primaryKey = 'id_medicamento';

    public $timestamps = false;

    protected $fillable = [
        'id_categoria',
        'nombre',
        'descripcion',
        'estado',
    ];

    protected function casts(): array
    {
        return [
            'estado' => 'boolean',
        ];
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MedicineCategory::class, 'id_categoria', 'id_categoria');
    }

    public function presentations(): HasMany
    {
        return $this->hasMany(MedicinePresentation::class, 'id_medicamento', 'id_medicamento');
    }
}
