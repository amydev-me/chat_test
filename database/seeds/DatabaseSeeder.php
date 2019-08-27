<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $admins = [
            [
                'display_name' => 'Amy Pyae Phyo Naing',
                'phone' => '0943011736',
                'password' => bcrypt('password')
                
            ],
            [
                'display_name' => 'May Thazin Oo',
                'phone' => '092012345',
                'password' => bcrypt('password')
                
            ],
            [
                'display_name' => 'Huai Siam',
                'phone' => '092012346',
                'password' => bcrypt('password')
                
            ]
        ];

        DB::table('users')->insert($admins);
    }
}
