<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use DB;

class CreatePaymentsTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('payment_types')->insert([
            ['payment_type_Name' => 'Efectivo'],
             ['payment_type_Name' => 'Dólares'],
            // ['payment_type_Name' => 'Transferencia'],
        ]);
    }
}
