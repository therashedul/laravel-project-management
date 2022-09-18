<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use DB;


class RoleHasPermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
          DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '1',
        ]);
        DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '2',
        ]); 
        DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '3',
        ]);
        DB::table('role_has_permissions')->insert([
           'role_id' => '1',
           'permission_id' => '4',
        ]);
         DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '5',
        ]);
        DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '6',
        ]);
        DB::table('role_has_permissions')->insert([
            'role_id' => '1',
            'permission_id' => '9',
        ]);
        DB::table('role_has_permissions')->insert([
           'role_id' => '1',
           'permission_id' => '13',
        ]);

       

    }
}
