<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;
class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
         DB::table('users')->insert([
            'role_id' => '1',
            'status_id' => '1',
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
        DB::table('users')->insert([
            'role_id' => '2',
            'status_id' => '1',
            'name' => 'executive',
            'email' => 'executive@gmail.com',
            'password' => bcrypt('123456789'),
        ]);
        DB::table('users')->insert([
            'role_id' => '3',
            'status_id' => '1',
            'name' => 'developer',
            'email' => 'developer@gmail.com',
            'password' => bcrypt('123456789'),
        ]);

    }
}
