<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
       // Insert some stuff
        DB::table('settings')->insert(
            array(
                'id' => 1,
                'email' => 'admin@example.com',
                'currency_id' => 1,
                'CompanyName' => 'Kenarh',
                'CompanyPhone' => '6315996770',
                'CompanyAdress' => '3618 Abia Martin Drive',
                'footer' => 'Kenarh - Best HRM & Project Management',
                'developed_by' => 'Alsol Technology Solution Pvt Ltd.',
                'logo' => 'LOGO1.png',
                'default_language' => 'en',
                'theme_color' => 'primary',
                'country' => '',
                'timezone' => 'UTC',
            )           
        );
    }
}
