<?php

namespace Database\Seeders;

use DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CreateUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
          // Crear usuario administrador
        DB::table('users')->insert([
            'User_FirstName' => 'Admin',
            'User_LastName' => 'Sistema',
            'User_Email' => 'admin@admin.com',
            'Password' => bcrypt('admin'),
            'User_Address' => 'Admin Address',
            'User_Phone' => '12345678',
            'User_Role' => 'Administrador',
            'Removed' => 0
        ]);
    }
}
