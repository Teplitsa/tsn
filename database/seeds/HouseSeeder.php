<?php

use Illuminate\Database\Seeder;

class HouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\House::insert([
            [
                'address' => 'Мира 32',
                'area' => 'Кировский',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ],

            [
                'address' => 'Ленина 40',
                'area' => 'Ленинский',
                'created_at' => Carbon\Carbon::now(),
                'updated_at' => Carbon\Carbon::now(),
            ],
        ]);
    }
}
