<?php

use Illuminate\Database\Seeder;

class SettingsTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('settings')->delete();
        
        \DB::table('settings')->insert(array (
            0 => 
            array (
                'id' => 1,
                'key' => 'general.site_name',
                'value' => 'WhatsWeb',
            ),
            1 => 
            array (
                'id' => 2,
                'key' => 'general.site_tagline',
                'value' => 'WhatsApp Link Generator',
            ),
            2 => 
            array (
                'id' => 3,
                'key' => 'general.site_country',
                'value' => '+62',
            ),
            3 => 
            array (
                'id' => 4,
                'key' => 'general.site_logo',
                'value' => 'Koala.jpg',
            ),
            4 => 
            array (
                'id' => 5,
                'key' => 'features.login_with_google',
                'value' => '0',
            ),
            5 => 
            array (
                'id' => 6,
                'key' => 'features.login_with_facebook',
                'value' => '0',
            ),
            6 => 
            array (
                'id' => 7,
                'key' => 'features.open_register',
                'value' => '1',
            ),
            7 => 
            array (
                'id' => 8,
                'key' => 'features.custom_slug',
                'value' => '1',
            ),
            8 => 
            array (
                'id' => 9,
                'key' => 'features.custom_slug_min',
                'value' => '6',
            ),
            9 => 
            array (
                'id' => 10,
                'key' => 'features.custom_slug_max',
                'value' => '10',
            ),
            10 => 
            array (
                'id' => 11,
                'key' => 'features.qr_code_size',
                'value' => '200',
            ),
            11 => 
            array (
                'id' => 12,
                'key' => 'features.shortlink_button_image',
                'value' => 'visit-me-1.png',
            ),
            12 => 
            array (
                'id' => 13,
                'key' => 'features.shortlink_button_alt',
                'value' => 'Visit Me',
            ),
            13 => 
            array (
                'id' => 14,
                'key' => 'features.whatsapp_button_image',
                'value' => 'chat-via-whatsapp.png',
            ),
            14 => 
            array (
                'id' => 15,
                'key' => 'features.whatsapp_button_alt',
                'value' => 'Chat via WhatsApp',
            ),
            15 => 
            array (
                'id' => 16,
                'key' => 'seo.image',
                'value' => 'preview.png',
            ),
            16 => 
            array (
                'id' => 17,
                'key' => 'seo.description',
                'value' => 'whatsapp click to chat generator',
            ),
            17 => 
            array (
                'id' => 18,
                'key' => 'seo.keywords',
                'value' => 'whatsapp click to chat, order link, generator',
            ),
            18 => 
            array (
                'id' => 19,
                'key' => 'seo.home_h1',
                'value' => 'WhatsApp Click to Chat Generator',
            ),
            19 => 
            array (
                'id' => 20,
                'key' => 'seo.home_description',
                'value' => 'Create your own WhatsApp Click to Chat easily, or shorten your URL, and you can see the statistics!',
            ),
        ));
        
        
    }
}