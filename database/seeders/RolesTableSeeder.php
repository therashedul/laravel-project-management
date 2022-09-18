<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'name' => 'admin',
            'slug' => 'admin',
        ]);

        DB::table('roles')->insert([
            'name' => 'executive',
            'slug' => 'executive',
        ]);
        
        DB::table('roles')->insert([
            'name' => 'developer',
            'slug' => 'developer',
        ]);
    }
}
