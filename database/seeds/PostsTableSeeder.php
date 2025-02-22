<?php

use Illuminate\Database\Seeder;

class PostsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('posts')->delete();
        
        \DB::table('posts')->insert(array (
            0 => 
            array (
                'id' => 1,
                'user_id' => NULL,
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<p>Hello, this is about us page!</p>',
                'type' => NULL,
                'show_in_menu' => 1,
                'sort' => 1,
                'created_at' => '2018-11-27 14:49:22',
                'updated_at' => '2018-11-27 14:49:22',
            ),
            1 => 
            array (
                'id' => 2,
                'user_id' => NULL,
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<p>This is contact us!</p>',
                'type' => NULL,
                'show_in_menu' => 1,
                'sort' => 2,
                'created_at' => '2018-11-27 14:49:37',
                'updated_at' => '2018-11-27 14:49:37',
            ),
            2 => 
            array (
                'id' => 3,
                'user_id' => NULL,
                'title' => 'Terms and Conditions',
                'slug' => 'terms-and-conditions',
                'content' => '<p>This is terms and conditions page</p>',
                'type' => NULL,
                'show_in_menu' => 0,
                'sort' => NULL,
                'created_at' => '2018-11-29 10:43:42',
                'updated_at' => '2018-11-29 10:43:42',
            ),
        ));
        
        
    }
}