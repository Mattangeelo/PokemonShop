<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class CategoriaUsuarioSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('categoria_usuarios')->insert([
            'nome' => 'cliente',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}