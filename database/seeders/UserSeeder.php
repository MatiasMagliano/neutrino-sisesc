<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $usuarios = [
            [
                'name' => 'Matías Magliano',
                'email' => 'magliano.matias@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('mmagliano'),
                'remember_token' => Str::random(10),
            ]
        ];

        foreach($usuarios as $usuario){
            User::create($usuario);
        }
    }
}
