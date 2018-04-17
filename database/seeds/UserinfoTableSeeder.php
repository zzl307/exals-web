<?php

use Illuminate\Database\Seeder;

class UserinfoTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 数据添加
        DB::table('userinfo')->insert([
        	['mac' => 'asdfghjkld1', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld2', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld4', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld5', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld6', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld7', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        	['mac' => 'asdfghjkld8', 'user_id' => 18900000000, 'id_type' => 'mobile', 'first_login' => '2017-09-08 15:05:12', 'last_login' => '2017-09-08 15:05:12'],
        ]);
    }
}
