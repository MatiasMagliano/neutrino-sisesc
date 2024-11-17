<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Materia extends Model
{
    protected $table = 'materias';

    protected $fillable = [
        'nombre',
        'descripcion',
        'curso_id'
    ];

    public function curso()
    {
        return $this->belongsTo(Curso::class);
    }
}
