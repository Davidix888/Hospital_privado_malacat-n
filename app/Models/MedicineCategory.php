<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicineCategory extends Model
{
    protected $table = 'categoria_medicamento';

    protected $primaryKey = 'id_categoria';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function medicines(): HasMany
    {
        return $this->hasMany(Medicine::class, 'id_categoria', 'id_categoria');
    }
}
