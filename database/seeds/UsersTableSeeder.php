<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        \DB::table('users')->delete();   
        \DB::table('users')->insert([
            0 => 
            [
                'id' => 1,
                'name' => 'Administrator',
                'email' => 'admin@admin.com',
                'email_verified_at' => date('Y-m-d H:i:s'),
                'password' => bcrypt(123456),
                'type' => 'ADMIN',
                'remember_token' => NULL,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
                'login_at' => NULL,
                'logout_at' => NULL,
            ],
        ]);
        
        
    }
}