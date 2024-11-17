<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
       Schema::create('estudiantes', function (Blueprint $table) {
            $table->id();
            $table->boolean('activo')->default(true);
            $table->foreignId('anio_lectivo_id')->constrained('anios_lectivos')->cascadeOnDelete();
            $table->foreignId('curso_id')->constrained('cursos')->cascadeOnDelete();
            $table->string('dni', 8)->unique();
            $table->string('nombre')->nullable(false);
            $table->string('apellido')->nullable(false);
            $table->date('f_nacimiento')->nullable(false);
            $table->string('email')->nullable(false);
            $table->string('telefono')->nullable(false);
            $table->string('direccion', 100)->nullable(false); // dirección normalizada con GeorefAR
            $table->boolean('egresado')->default(false);
            $table->mediumText('observaciones')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('estudiantes');
    }
};
