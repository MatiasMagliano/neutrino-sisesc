<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Estudiante extends Model
{
    use HasFactory;

    protected $table = 'estudiantes';

    protected $fillable = [
        'activo',
        'anio_lectivo_id',
        'curso_id',
        'dni',
        'nombre',
        'apellido',
        'f_nacimiento',
        'email',
        'telefono',
        'direccion',
        'egresado',
        'observaciones'
    ];

    protected $casts = [
         'f_nacimiento' => 'date',
    ];

    // Scope de año lectivo
    public function scopeActivo($query)
    {
        return $query->where('activo', true);
    }

    // Relaciones
    public function anioLectivo()
    {
        return $this->belongsTo(AnioLectivo::class);
    }

    public function cursos() {
        return $this->belongsTo(Curso::class, 'curso_id');
    }

    public function materias() {
        return $this->hasMany(Materia::class,'materia_id');
    }

    // public function notas() {
    //     return $this->hasMany(Nota::class);
    // }
}
