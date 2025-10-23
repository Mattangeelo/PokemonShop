<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use Categoria_UsuarioSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Isso geralmente chama o UserSeeder por padrão
        // $this->call(UserSeeder::class); // Se não precisar de usuários, pode comentar essa linha ou deletar o UserSeeder
        $this->call(AdminSeeder::class);
    }
}

