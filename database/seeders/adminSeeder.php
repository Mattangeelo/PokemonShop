<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('usuarios')->insert([
            'email' => 'admin@admin.com',
            'nome' => 'Matheus Angelo',
            'senha' => Hash::make('123456'),
            'telefone' => '(44)99804-0796',
            'cpf' => '436.433.078-70',
            'id_categoria'=> 1,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
