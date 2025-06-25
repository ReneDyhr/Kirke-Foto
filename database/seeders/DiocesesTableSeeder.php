<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DiocesesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('dioceses')->delete();
        
        \DB::table('dioceses')->insert(array (
            0 => 
            array (
                'id' => 1,
                'name' => 'Københavns Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:26',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            1 => 
            array (
                'id' => 2,
                'name' => 'Helsingør Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:40',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            2 => 
            array (
                'id' => 3,
                'name' => 'Roskilde Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:47',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            3 => 
            array (
                'id' => 4,
                'name' => 'Lolland-Falsters Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:51',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            4 => 
            array (
                'id' => 5,
                'name' => 'Fyens Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:55',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            5 => 
            array (
                'id' => 6,
                'name' => 'Aalborg Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:23:57',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            6 => 
            array (
                'id' => 7,
                'name' => 'Viborg Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:24:01',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            7 => 
            array (
                'id' => 8,
                'name' => 'Århus Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:24:18',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            8 => 
            array (
                'id' => 9,
                'name' => 'Ribe Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:24:21',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
            9 => 
            array (
                'id' => 10,
                'name' => 'Haderslev Stift',
                'description' => '',
                'created_at' => '2022-05-01 18:24:24',
                'updated_at' => '2025-06-25 11:34:17',
                'deleted_at' => NULL,
            ),
        ));
        
        
    }
}