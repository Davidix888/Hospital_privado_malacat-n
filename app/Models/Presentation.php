<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Presentation extends Model
{
    protected $table = 'presentacion';

    protected $primaryKey = 'id_presentacion';

    public $timestamps = false;

    protected $fillable = [
        'nombre',
        'descripcion',
    ];

    public function medicinePresentations(): HasMany
    {
        return $this->hasMany(MedicinePresentation::class, 'id_presentacion', 'id_presentacion');
    }
}
