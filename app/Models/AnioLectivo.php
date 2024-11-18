<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Model;
use App\Models\Materia;
use App\Models\Curso;

use App\Enums\Status;

class AnioLectivo extends Model
{
    protected $table = 'anios_lectivos';

    protected $fillable = [
        'anio',
        'fecha_inicio',
        'fecha_fin',
        'activo',
        'descripcion'
    ];

    public $casts = [
        'activo' => Status::class,
        'anio' => 'integer',
        'fecha_inicio' => 'datetime:Y-m-d',
        'fecha_fin' => 'datetime:Y-m-d'
    ];

    // Scope de año lectivo
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Relaciones
    public function matricula()
    {
        return $this->hasMany(Curso::class, 'anio_lectivo_id');
    }

    public function materias()
    {
        return $this->hasManyThrough(Materia::class, Curso::class);
    }

    public function estudiantes()
    {
        return $this->hasMany(Estudiante::class);
    }
}
