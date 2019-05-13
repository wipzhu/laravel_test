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
        // 生成一条数据
//        \DB::table('manager')->insert(
//            ['username' => 'admin', 'password' => bcrypt('qweasd11')]
//        );

        // 批量生成数据
        $faker =\Faker\Factory::create('zh_CN');
        for ($i=0;$i<50;$i++){
            \App\Model\Manager::create([
                'username' => $faker->name,
                'password' => bcrypt('qweasd11'),
            ]);
        }
    }
}
