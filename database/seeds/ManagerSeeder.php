<?php

use Illuminate\Database\Seeder;

class ManagerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \DB::table('manager')->insert(
            ['username' => 'admin', 'password' => bcrypt('123456')]
        );
    }
}
