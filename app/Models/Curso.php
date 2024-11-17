<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Curso extends Model
{
    protected $table = 'cursos';

    protected $fillable = [
        'nombre',
        'ciclo',
        'division',
        'turno',
        'descripcion',
        'anio_lectivo_id'
    ];

    public function materias()
    {
        return $this->hasMany(Materia::class, 'curso_id');
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class, 'curso_id');
    }

    public function anioLectivo()
    {
        return $this->belongsTo(AnioLectivo::class);
    }
}
