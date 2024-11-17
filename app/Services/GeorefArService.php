<?php
// app/Services/GeorefArService.php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class GeorefArService
{
    /**
     * Obtiene las provincias de la API Georef-Ar.
     * Cachea el resultado por 24 horas para evitar múltiples llamadas.
     *
     * @return array
     */
    public function getProvinces(): array
    {
        return Cache::remember('provincias', now()->addHours(24), function () {
            $response = Http::get('https://apis.datos.gob.ar/georef/api/provincias');
            return $response->json()['provincias'] ?? [];
        });
    }

    public function getLocalities($provinciaId): array
    {
        if (!$provinciaId) {
            return [];
        }

        return Cache::remember("localidades_{$provinciaId}", now()->addHours(24), function () use ($provinciaId) {
            $response = Http::get('https://apis.datos.gob.ar/georef/api/localidades', [
                'provincia' => $provinciaId,
                'max' => 1000, // Ajusta según tus necesidades
            ]);

            return $response->json()['localidades'] ?? [];
        });
    }

    public function normalizeAddress(string $address): array
    {
        // Realizamos la solicitud a la API de Georef-Ar para normalizar la dirección
        $response = Http::get('https://apis.datos.gob.ar/georef/api/direcciones', [
            'direccion' => $address,
            'max' => 1,  // Solo tomamos la primera coincidencia
        ]);

        return $response->json()['direcciones'] ?? [];
    }
}
