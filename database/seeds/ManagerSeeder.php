<?php

use App\Model\Manager;
use Faker\Factory;
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
        // 生成一条数据
//        \DB::table('manager')->insert(
//            ['username' => 'admin', 'password' => bcrypt('qweasd11')]
//        );

        // 批量生成数据
        $faker = Factory::create('zh_CN');
        for ($i = 0; $i < 10; $i++) {
            Manager::create([
                'username' => $faker->userName,
                'mg_sex' => ['女', '男'][rand(0, 1)],
                'mg_phone' => $faker->phoneNumber,
                'mg_email' => $faker->email,
                'password' => bcrypt('qweasd11'),
            ]);
        }
    }
}
