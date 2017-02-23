<?php

use Illuminate\Database\Seeder;

class Roles extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //ЗАПУСКАТЬ ОДИН РАЗ
        $role=\App\Role::where('keyword','tenant')->first();
        $role->role='Управляющий';
        $role->save();
    }
}
